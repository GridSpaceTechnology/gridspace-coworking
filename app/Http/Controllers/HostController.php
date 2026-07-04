<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HostController extends Controller
{
    public function calendar(Request $request): View
    {
        $user = Auth::user();

        if (! $user->isHost()) {
            abort(403, 'Host access only.');
        }

        $listingIds = $user->listings()->pluck('id');

        $bookings = $listingIds->isEmpty()
            ? collect()
            : Booking::whereIn('listing_id', $listingIds)
                ->with(['listing.images', 'user', 'space'])
                ->orderBy('check_in_date')
                ->get();

        $pendingRequests = $bookings->where('status', 'pending')->values();

        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $current = \Carbon\Carbon::create($year, $month, 1);
        $selectedDay = $request->input('day');

        $calendarBookings = $bookings->filter(function ($booking) use ($current) {
            return $booking->check_in_date?->month === $current->month
                && $booking->check_in_date?->year === $current->year;
        });

        $daysInMonth = $current->daysInMonth;
        $startDayOfWeek = $current->copy()->startOfMonth()->dayOfWeek;
        $prevMonth = $current->copy()->subMonth();
        $nextMonth = $current->copy()->addMonth();

        $selectedDateBookings = collect();
        if ($selectedDay) {
            $selectedDate = $current->copy()->day((int) $selectedDay);
            $selectedDateBookings = $bookings->filter(
                fn ($b) => $b->check_in_date?->isSameDay($selectedDate)
            )->values();
        }

        return view('host.calendar', compact(
            'bookings',
            'pendingRequests',
            'current',
            'daysInMonth',
            'startDayOfWeek',
            'prevMonth',
            'nextMonth',
            'calendarBookings',
            'selectedDay',
            'selectedDateBookings'
        ));
    }

    public function earnings(Request $request): View
    {
        $user = Auth::user();

        if (! $user->isHost()) {
            abort(403, 'Host access only.');
        }

        $listingIds = $user->listings()->pluck('id');
        $period = $request->input('period', 'weekly');

        $bookings = $listingIds->isEmpty()
            ? collect()
            : Booking::whereIn('listing_id', $listingIds)
                ->with(['listing', 'user'])
                ->orderByDesc('created_at')
                ->get();

        $confirmed = $bookings->whereIn('status', ['confirmed', 'completed']);

        $totalEarnings = $confirmed->sum('total_price');
        $totalBookings = $confirmed->count();
        $avgBookingValue = $totalBookings > 0 ? $totalEarnings / $totalBookings : 0;

        $chartLabels = [];
        $chartBookings = [];
        $chartPayouts = [];

        if ($period === 'monthly') {
            for ($i = 5; $i >= 0; $i--) {
                $monthStart = now()->subMonths($i)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();
                $chartLabels[] = $monthStart->format('M Y');
                $monthBookings = $confirmed->filter(
                    fn ($b) => $b->created_at->between($monthStart, $monthEnd)
                );
                $chartBookings[] = $monthBookings->sum('total_price');
                $chartPayouts[] = $monthBookings->sum('total_price') * 0.9;
            }
        } else {
            for ($i = 3; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekEnd = $weekStart->copy()->endOfWeek();
                $chartLabels[] = 'Week ' . (4 - $i);
                $weekBookings = $confirmed->filter(
                    fn ($b) => $b->created_at->between($weekStart, $weekEnd)
                );
                $chartBookings[] = $weekBookings->sum('total_price');
                $chartPayouts[] = $weekBookings->sum('total_price') * 0.9;
            }
        }

        $maxChart = max(1, ...array_merge($chartBookings, $chartPayouts, [0]));

        return view('host.earnings', compact(
            'user',
            'bookings',
            'totalEarnings',
            'totalBookings',
            'avgBookingValue',
            'period',
            'chartLabels',
            'chartBookings',
            'chartPayouts',
            'maxChart'
        ));
    }
}
