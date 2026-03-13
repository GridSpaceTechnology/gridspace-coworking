<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeaturedRequestController extends Controller
{
    public function create()
    {
        $listings = Listing::where('user_id', Auth::id())
            ->where('status', 'published')
            ->with(['category', 'city'])
            ->get();

        return view('featured.request', compact('listings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'plan' => 'required|in:featured,premium',
            'duration' => 'required|integer|min:1|max:12',
            'contact_email' => 'required|email',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if user owns the listing
        $listing = Listing::findOrFail($validated['listing_id']);
        if ($listing->user_id !== Auth::id()) {
            return back()->with('error', 'You can only request featured status for your own listings.');
        }

        // Check if listing is already featured or has a pending request
        if ($listing->featured || $listing->featured_request_status === 'pending') {
            return back()->with('error', 'This listing already has a featured request or is already featured.');
        }

        // Calculate amount
        $planPrice = $validated['plan'] === 'premium' ? 12000 : 5000;
        $amount = $planPrice * $validated['duration'];

        // Update the listing with featured request data
        $listing->update([
            'featured_request_status' => 'pending',
            'featured_plan' => $validated['plan'],
            'featured_duration' => $validated['duration'],
            'featured_amount' => $amount,
            'featured_contact_email' => $validated['contact_email'],
            'featured_notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Your featured request has been submitted! We will contact you within 24 hours for payment.');
    }

    public function index()
    {
        $requests = Listing::with(['category', 'city', 'user'])
            ->whereNotNull('featured_request_status')
            ->where('featured_request_status', '!=', 'none')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('featured.admin-index', compact('requests'));
    }

    public function approve(Listing $listing)
    {
        $listing->update([
            'featured_request_status' => 'active',
            'featured' => true,
            'featured_starts_at' => now(),
            'featured_expires_at' => now()->addMonths($listing->featured_duration),
        ]);

        return back()->with('success', 'Featured request approved and listing is now featured!');
    }

    public function reject(Listing $listing)
    {
        $listing->update([
            'featured_request_status' => 'rejected',
            'featured' => false,
        ]);

        return back()->with('success', 'Featured request rejected.');
    }
}
