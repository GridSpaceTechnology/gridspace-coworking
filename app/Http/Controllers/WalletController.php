<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        if ($user->isHost()) {
            $listingIds = $user->listings()->pluck('id');

            $bookings = Booking::whereIn('listing_id', $listingIds)
                ->with(['listing.images', 'listing.city', 'user'])
                ->orderByDesc('created_at')
                ->get();

            $stats = (object) [
                'balance' => (float) $user->wallet_balance,
                'total_earned' => $bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
                'pending' => $bookings->where('status', 'pending')->sum('total_price'),
                'this_month' => $bookings
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->sum('total_price'),
            ];

            $transactions = $bookings;
            $isHost = true;
        } else {
            $bookings = Booking::where('user_id', $user->id)
                ->with(['listing.images', 'listing.city'])
                ->orderByDesc('created_at')
                ->get();

            $stats = (object) [
                'balance' => (float) $user->wallet_balance,
                'total_spent' => $bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
                'pending' => $bookings->where('status', 'pending')->sum('total_price'),
                'this_month' => $bookings
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->sum('total_price'),
            ];

            $transactions = $bookings;
            $isHost = false;
        }

        return view('wallet.index', compact('user', 'stats', 'transactions', 'isHost'));
    }
}
