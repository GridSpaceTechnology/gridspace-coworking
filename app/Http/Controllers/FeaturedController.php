<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class FeaturedController extends Controller
{
    public function index()
    {
        $featuredListings = Listing::where('featured', true)
            ->with(['category', 'city', 'images'])
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        return view('featured', compact('featuredListings'));
    }
}
