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
            'total_users' => User::count(),
            'total_hosts' => User::where('role', 'host')->count(),
            'pending_hosts' => User::where('role', 'host')->where('approved', false)->count(),
            'total_inquiries' => \App\Models\Inquiry::count(),
        ];

        $recentListings = Listing::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentInquiries = \App\Models\Inquiry::with(['listing', 'listing.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.index', compact('stats', 'recentListings', 'recentInquiries'));
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
}
