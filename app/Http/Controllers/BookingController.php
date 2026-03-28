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
        // Get existing confirmed bookings for this listing
        $bookedDates = \App\Models\Booking::where('listing_id', $listing->id)
            ->where('status', 'confirmed')
            ->get(['check_in_date', 'check_out_date']);

        return view('bookings.create', compact('listing', 'bookedDates'));
    }

    public function store(Request $request, Listing $listing)
    {
        // Check if listing has a valid price
        if (!$listing->price || $listing->price <= 0) {
            return back()->with('error', 'This listing does not have a valid price. Please contact the host.');
        }

        $validated = $request->validate([
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'number_of_people' => 'required|integer|min:1|max:' . ($listing->capacity ?? 10),
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check for booking conflicts
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);

        // Check if there are any existing bookings that overlap with the requested dates
        $conflictingBookings = \App\Models\Booking::where('listing_id', $listing->id)
            ->where('status', 'confirmed') // Only check confirmed bookings
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($query) use ($checkIn, $checkOut) {
                    // Check if new booking starts during an existing booking
                    $query->where('check_in_date', '<=', $checkIn)
                          ->where('check_out_date', '>', $checkIn);
                })->orWhere(function ($query) use ($checkIn, $checkOut) {
                    // Check if new booking ends during an existing booking
                    $query->where('check_in_date', '<', $checkOut)
                          ->where('check_out_date', '>=', $checkOut);
                })->orWhere(function ($query) use ($checkIn, $checkOut) {
                    // Check if new booking completely contains an existing booking
                    $query->where('check_in_date', '>=', $checkIn)
                          ->where('check_out_date', '<=', $checkOut);
                });
            })
            ->get();

        if ($conflictingBookings->count() > 0) {
            // Build detailed error message with conflicting dates
            $conflictDetails = [];
            foreach ($conflictingBookings as $conflict) {
                $conflictDetails[] = sprintf(
                    '%s to %s',
                    $conflict->check_in_date->format('F j, Y'),
                    $conflict->check_out_date->format('F j, Y')
                );
            }

            $errorMessage = 'The selected dates conflict with existing bookings for this listing.' .
                           ' The following dates are already booked: ' .
                           implode(', ', $conflictDetails) .
                           '. Please choose different dates.';

            return back()->with('error', $errorMessage)
                   ->withInput();
        }

        // Calculate total price based on number of nights (minimum 1 night)
        $nights = max(1, $checkIn->diffInDays($checkOut));
        $price = (float) ($listing->price ?? 0);
        $totalPrice = $nights * $price * $validated['number_of_people'];

        $booking = Booking::create([
            'listing_id' => $listing->id,
            'user_id' => Auth::id(),
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'number_of_people' => $validated['number_of_people'],
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

    public function index(Request $request)
    {
        $query = Booking::where('user_id', Auth::id())
            ->with('listing')
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

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
