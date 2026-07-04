<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\ListingSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Listing $listing, ListingSpace $space)
    {
        abort_unless($space->listing_id === $listing->id, 404);
        abort_unless($space->is_active, 404);

        $listing->load(['images', 'category', 'city']);
        $space->load(['category', 'amenities']);

        $bookedDates = Booking::where('listing_space_id', $space->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['check_in_date', 'check_out_date']);

        return view('bookings.create', compact('listing', 'space', 'bookedDates'));
    }

    public function store(Request $request, Listing $listing, ListingSpace $space)
    {
        abort_unless($space->listing_id === $listing->id, 404);
        abort_unless($space->is_active, 404);

        if (! $space->price || $space->price <= 0) {
            return back()->with('error', 'This space does not have a valid price. Please contact the host.');
        }

        $validated = $request->validate([
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'number_of_people' => 'required|integer|min:1|max:' . max(1, (int) $space->capacity),
            'notes' => 'nullable|string|max:1000',
        ]);

        $checkIn = \Carbon\Carbon::parse($validated['check_in_date'])->startOfDay();
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date'])->startOfDay();

        if (! $space->isAvailableBetween($checkIn, $checkOut)) {
            $conflicts = Booking::where('listing_space_id', $space->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('check_in_date', '<', $checkOut)
                ->where('check_out_date', '>', $checkIn)
                ->get();

            $conflictDetails = $conflicts->map(fn ($conflict) => sprintf(
                '%s to %s',
                $conflict->check_in_date->format('F j, Y'),
                $conflict->check_out_date->format('F j, Y')
            ))->all();

            return back()
                ->with('error', 'This space is already booked for: ' . implode(', ', $conflictDetails) . '. Please choose different dates.')
                ->withInput();
        }

        $days = max(1, $checkIn->diffInDays($checkOut));
        $units = match ($space->price_period) {
            'hour' => $days * 8, // 8 billable hours per day
            'week' => max(1, (int) ceil($days / 7)),
            'month' => max(1, (int) ceil($days / 30)),
            default => $days, // per day
        };
        $totalPrice = $units * (float) $space->price * (int) $validated['number_of_people'];

        $booking = Booking::create([
            'listing_id' => $listing->id,
            'listing_space_id' => $space->id,
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

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Booking request submitted successfully!');
    }

    public function confirmation(Booking $booking)
    {
        $booking->load(['listing.images', 'listing.category', 'listing.city', 'space.category']);

        return view('bookings.confirmation', compact('booking'));
    }

    public function index(Request $request)
    {
        $userId = Auth::id();

        $stats = Booking::where('user_id', $userId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            ")
            ->first();

        $bookings = Booking::with(['listing.images', 'listing.city', 'space'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings', 'stats'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $isOwner = $booking->user_id === $user->id;
        $isHost = $booking->listing && $booking->listing->user_id === $user->id;
        $isAdmin = $user->isAdmin();

        if (! $isOwner && ! $isHost && ! $isAdmin) {
            abort(403);
        }

        $allowed = $isHost && ! $isAdmin
            ? ['confirmed', 'cancelled']
            : ['pending', 'confirmed', 'cancelled', 'completed'];

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', $allowed),
        ]);

        $booking->update(['status' => $validated['status']]);

        $message = match ($validated['status']) {
            'confirmed' => 'Booking accepted. The space is now booked for those dates.',
            'cancelled' => 'Booking declined.',
            default => 'Booking status updated.',
        };

        return back()->with('success', $message);
    }
}
