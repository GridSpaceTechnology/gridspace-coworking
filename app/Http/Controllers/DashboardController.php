<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        if ($user->isHost()) {
            $featureRequests = \App\Models\FeatureRequest::with(['listing', 'listing.images'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard-host', compact('featureRequests'));
        }

        $featuredListings = Listing::where('featured', true)
            ->where('status', 'published')
            ->with(['images', 'category', 'city'])
            ->latest()
            ->take(6)
            ->get();

        $recommendedListing = $featuredListings->first()
            ?? Listing::where('status', 'published')
                ->with(['images', 'category', 'city'])
                ->latest()
                ->first();

        $recentBookings = Booking::with('listing')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard-guest', compact('featuredListings', 'recommendedListing', 'recentBookings'));
    }
}
