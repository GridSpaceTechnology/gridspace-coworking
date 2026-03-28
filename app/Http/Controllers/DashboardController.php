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
            return view('dashboard-host');
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
