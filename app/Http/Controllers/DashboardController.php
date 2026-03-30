<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        // Route to appropriate dashboard based on user role
        if ($user->isHost()) {
            // Load host's feature requests
            $featureRequests = \App\Models\FeatureRequest::with(['listing', 'listing.images'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard-host', compact('featureRequests'));
        } else {
            // Get featured listings for guest dashboard
            $featuredListings = \App\Models\Listing::where('featured', true)
                ->with(['images', 'category'])
                ->latest()
                ->take(6)
                ->get();

            return view('dashboard-guest', compact('featuredListings'));
        }
    }
}
