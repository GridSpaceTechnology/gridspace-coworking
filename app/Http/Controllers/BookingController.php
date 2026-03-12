<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Listing $listing)
    {
        return view('bookings.create', compact('listing'));
    }

    public function store(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate total price based on number of nights
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $nights * $listing->price;

        $booking = Booking::create([
            'listing_id' => $listing->id,
            'user_id' => Auth::id(),
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        // Notify host (in real app, would send notification)
        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Booking request submitted successfully!');
    }

    public function confirmation(Booking $booking)
    {
        return view('bookings.confirmation', compact('booking'));
    }

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('listing')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        // Check if user owns this booking
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status updated successfully!');
    }
}
