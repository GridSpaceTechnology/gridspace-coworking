<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\ListingAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryReceived;

class InquiryController extends Controller
{
    /**
     * Store a newly created inquiry in storage.
     */
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

        // Track inquiry analytics
        ListingAnalytic::trackInquiry(
            $validated['listing_id'],
            $request->ip(),
            $request->userAgent()
        );

        // Send email notification to admin
        try {
            Mail::to('admin@gridspace.com')->send(new InquiryReceived($inquiry));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send inquiry email: ' . $e->getMessage());
        }

        return back()
            ->with('success', 'Your inquiry has been sent successfully! The workspace provider will contact you soon.');
    }
}
