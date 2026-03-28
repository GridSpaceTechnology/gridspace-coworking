<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_listings' => Listing::count(),
            'published_listings' => Listing::where('status', 'published')->count(),
            'featured_listings' => Listing::where('featured', true)->count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_hosts' => User::where('role', 'host')->count(),
            'pending_hosts' => User::where('role', 'host')->where('approved', false)->count(),
            'total_inquiries' => \App\Models\Inquiry::count(),
            'total_bookings' => \App\Models\Booking::count(),
            'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
            'pending_featured_requests' => Listing::where('featured_request_status', 'pending')->where('featured', false)->count(),
        ];

        $recentListings = Listing::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentInquiries = \App\Models\Inquiry::with(['listing', 'listing.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentBookings = \App\Models\Booking::with(['listing', 'listing.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $featuredRequests = Listing::with(['user', 'category'])
            ->where('featured_request_status', 'pending')
            ->where('featured', false)  // Exclude already featured listings
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.index', compact('stats', 'recentListings', 'recentInquiries', 'recentBookings', 'featuredRequests'));
    }

    /**
     * Show pending listings for admin approval.
     */
    public function pendingListings()
    {
        $pendingListings = Listing::with(['user', 'category', 'city'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pending-listings', compact('pendingListings'));
    }

    /**
     * Toggle featured status of a listing.
     */
    public function toggleFeatured(Request $request, Listing $listing)
    {
        $listing->featured = !$listing->featured;
        $listing->save();

        $status = $listing->featured ? 'featured' : 'unfeatured';

        return redirect()->back()
            ->with('success', "Listing {$status} successfully!");
    }

    /**
     * Approve a pending listing.
     */
    public function approveListing(Listing $listing)
    {
        $listing->status = 'published';
        $listing->save();

        return redirect()->back()
            ->with('success', 'Listing approved successfully!');
    }

    /**
     * Reject a pending listing.
     */
    public function rejectListing(Listing $listing)
    {
        $listing->status = 'draft';
        $listing->save();

        return redirect()->back()
            ->with('success', 'Listing rejected successfully!');
    }

    /**
     * Bulk approve all pending listings.
     */
    public function bulkApprove(Request $request)
    {
        $listingIds = $request->input('listings', []);

        foreach ($listingIds as $listingId) {
            $listing = Listing::find($listingId);
            if ($listing) {
                $listing->status = 'published';
                $listing->save();
            }
        }

        $count = count($listingIds);
        return redirect()->back()
            ->with('success', "{$count} listing(s) approved successfully!");
    }

    /**
     * Show all bookings.
     */
    public function indexBookings()
    {
        $bookings = \App\Models\Booking::with(['listing', 'listing.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show booking details.
     */
    public function showBooking(\App\Models\Booking $booking)
    {
        $booking->load(['listing', 'listing.user', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update booking status.
     */
    public function updateBookingStatus(Request $request, \App\Models\Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', "Booking status updated to {$validated['status']} successfully!");
    }
}
