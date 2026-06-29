<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Inquiry;
use App\Models\ListingAnalytic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryReceived;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $viewerIsHost = $user->isHost() && ! $user->isAdmin();

        if ($user->isAdmin()) {
            $inquiries = Inquiry::with(['listing.images', 'listing.city', 'listing.user', 'chatMessages'])
                ->latest()
                ->get();
        } elseif ($user->isHost()) {
            $listingIds = $user->listings()->pluck('id');
            $inquiries = Inquiry::whereIn('listing_id', $listingIds)
                ->with(['listing.images', 'listing.city', 'listing.user', 'chatMessages'])
                ->latest('updated_at')
                ->get();
        } else {
            $inquiries = Inquiry::where('email', $user->email)
                ->with(['listing.images', 'listing.city', 'listing.user', 'chatMessages'])
                ->latest('updated_at')
                ->get();
        }

        $selectedId = $request->integer('id') ?: $inquiries->first()?->id;
        $selected = $selectedId ? $inquiries->firstWhere('id', $selectedId) : null;

        if ($selected) {
            $selected->markReadFor($viewerIsHost || $user->isAdmin());
            $selected->load('chatMessages');
        }

        return view('inquiries.index', [
            'inquiries' => $inquiries,
            'selected' => $selected,
            'isHost' => $user->isHost(),
            'isAdmin' => $user->isAdmin(),
            'viewerIsHost' => $viewerIsHost || $user->isAdmin(),
        ]);
    }

    public function show(Inquiry $inquiry)
    {
        return redirect()->route('inquiries.index', ['id' => $inquiry->id]);
    }

    public function storeMessage(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeInquiry($inquiry);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = Auth::user();
        $isHost = ($user->isHost() || $user->isAdmin()) && $this->userCanActAsHost($inquiry, $user);

        ChatMessage::create([
            'inquiry_id' => $inquiry->id,
            'user_id' => $user->id,
            'is_host' => $isHost,
            'body' => $validated['message'],
        ]);

        if ($isHost) {
            $inquiry->update(['contacted' => true]);
        }

        $inquiry->touch();

        return redirect()
            ->route('inquiries.index', ['id' => $inquiry->id])
            ->with('success', 'Message sent.');
    }

    public function toggleContacted(Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeInquiry($inquiry);

        $inquiry->update(['contacted' => ! $inquiry->contacted]);

        return redirect()
            ->route('inquiries.index', ['id' => $inquiry->id])
            ->with('success', $inquiry->contacted ? 'Marked as replied.' : 'Marked as unread.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:2000',
            'newsletter_opt_in' => 'nullable|boolean',
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['newsletter_opt_in'] = $request->boolean('newsletter_opt_in', false);

        $inquiry = Inquiry::create($validated);

        ChatMessage::create([
            'inquiry_id' => $inquiry->id,
            'user_id' => Auth::id(),
            'is_host' => false,
            'body' => $validated['message'],
        ]);

        ListingAnalytic::trackInquiry(
            $validated['listing_id'],
            $request->ip(),
            $request->userAgent()
        );

        try {
            Mail::to('admin@gridspace.com')->send(new InquiryReceived($inquiry));
        } catch (\Exception $e) {
            \Log::error('Failed to send inquiry email: ' . $e->getMessage());
        }

        return back()
            ->with('success', 'Your inquiry has been sent successfully! The workspace provider will contact you soon.');
    }

    private function authorizeInquiry(Inquiry $inquiry): void
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isHost()) {
            abort_unless(
                $user->listings()->where('id', $inquiry->listing_id)->exists(),
                403
            );

            return;
        }

        abort_unless($inquiry->email === $user->email, 403);
    }

    private function userCanActAsHost(Inquiry $inquiry, $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isHost() && $user->listings()->where('id', $inquiry->listing_id)->exists();
    }
}
