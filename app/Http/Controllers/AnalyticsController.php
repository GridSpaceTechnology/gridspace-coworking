<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingAnalytic;
use App\Models\ListingSpace;
use App\Services\AdminBulkDeleteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): View
    {
        $listings = $this->buildListingQuery($request)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $analyticsData = $this->mapAnalyticsData($listings);

        $summary = [
            'total_listings' => Listing::count(),
            'total_spaces' => ListingSpace::where('is_active', true)->count(),
            'booked_spaces' => ListingSpace::where('is_active', true)
                ->whereHas('bookings', fn ($q) => $q->where('status', 'confirmed')->where('check_out_date', '>', now()))
                ->count(),
            'total_views' => ListingAnalytic::where('event_type', 'view')->count(),
            'phone_clicks' => ListingAnalytic::where('event_type', 'phone_click')->count(),
            'whatsapp_clicks' => ListingAnalytic::where('event_type', 'whatsapp_click')->count(),
            'total_inquiries' => ListingAnalytic::where('event_type', 'inquiry')->count(),
        ];

        $categories = Category::orderBy('name')->get();

        return view('admin.analytics.index', compact('analyticsData', 'listings', 'summary', 'categories'));
    }

    public function export(Request $request)
    {
        $listings = $this->buildListingQuery($request)->get();

        $csvData = [[
            'Listing Name', 'Category', 'Host', 'Spaces', 'Booked Spaces', 'Available Spaces',
            'Booking Status', 'Views', 'Views 7d', 'Views 30d',
            'Phone Clicks', 'Phone 7d', 'Phone 30d',
            'WhatsApp Clicks', 'WhatsApp 7d', 'WhatsApp 30d',
            'Inquiries', 'Created At',
        ]];

        foreach ($listings as $listing) {
            $metrics = $this->metricsForListing($listing);
            $csvData[] = [
                $listing->name,
                $listing->category?->name ?? '',
                $listing->user?->display_name ?? $listing->user?->name ?? '',
                $metrics['spaces_count'],
                $metrics['booked_spaces_count'],
                $metrics['available_spaces_count'],
                $metrics['booking_status'],
                $metrics['total_views'],
                $metrics['views_7d'],
                $metrics['views_30d'],
                $metrics['phone_clicks'],
                $metrics['phone_7d'],
                $metrics['phone_30d'],
                $metrics['whatsapp_clicks'],
                $metrics['whatsapp_7d'],
                $metrics['whatsapp_30d'],
                $metrics['inquiries'],
                $listing->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'gridspace-analytics-' . date('Y-m-d') . '.csv';

        return response()->stream(function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function bulkDeleteListings(Request $request, AdminBulkDeleteService $bulkDelete): RedirectResponse
    {
        $ids = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:listings,id',
        ])['ids'];

        $count = $bulkDelete->deleteListings($ids);

        return back()->with('success', "{$count} listing(s) deleted successfully.");
    }

    private function buildListingQuery(Request $request)
    {
        $query = Listing::with(['user', 'category', 'images'])
            ->withCount([
                'analytics as total_views_count' => fn ($q) => $q->where('event_type', 'view'),
                'analytics as phone_clicks_count' => fn ($q) => $q->where('event_type', 'phone_click'),
                'analytics as whatsapp_clicks_count' => fn ($q) => $q->where('event_type', 'whatsapp_click'),
                'analytics as inquiries_count' => fn ($q) => $q->where('event_type', 'inquiry'),
                'analytics as views_7d_count' => fn ($q) => $q->where('event_type', 'view')->where('created_at', '>=', now()->subDays(7)),
                'analytics as views_30d_count' => fn ($q) => $q->where('event_type', 'view')->where('created_at', '>=', now()->subDays(30)),
                'analytics as phone_7d_count' => fn ($q) => $q->where('event_type', 'phone_click')->where('created_at', '>=', now()->subDays(7)),
                'analytics as phone_30d_count' => fn ($q) => $q->where('event_type', 'phone_click')->where('created_at', '>=', now()->subDays(30)),
                'analytics as whatsapp_7d_count' => fn ($q) => $q->where('event_type', 'whatsapp_click')->where('created_at', '>=', now()->subDays(7)),
                'analytics as whatsapp_30d_count' => fn ($q) => $q->where('event_type', 'whatsapp_click')->where('created_at', '>=', now()->subDays(30)),
                'spaces as spaces_count' => fn ($q) => $q->where('is_active', true),
                'spaces as booked_spaces_count' => fn ($q) => $q->where('is_active', true)
                    ->whereHas('bookings', fn ($b) => $b->where('status', 'confirmed')->where('check_out_date', '>', now())),
            ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('firstname', 'like', "%{$search}%")
                            ->orWhere('lastname', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        if ($request->filled('booking_status')) {
            if ($request->booking_status === 'booked') {
                $query->whereHas('spaces', fn ($q) => $q->where('is_active', true)
                    ->whereHas('bookings', fn ($b) => $b->where('status', 'confirmed')->where('check_out_date', '>', now())));
            } elseif ($request->booking_status === 'available') {
                $query->whereDoesntHave('spaces', fn ($q) => $q->where('is_active', true)
                    ->whereHas('bookings', fn ($b) => $b->where('status', 'confirmed')->where('check_out_date', '>', now())));
            }
        }

        return match ($request->input('sort', 'created_desc')) {
            'views_desc' => $query->orderByDesc('total_views_count'),
            'phone_desc' => $query->orderByDesc('phone_clicks_count'),
            'whatsapp_desc' => $query->orderByDesc('whatsapp_clicks_count'),
            'spaces_desc' => $query->orderByDesc('spaces_count'),
            'booked_desc' => $query->orderByDesc('booked_spaces_count'),
            'name_asc' => $query->orderBy('name'),
            default => $query->orderByDesc('created_at'),
        };
    }

    private function mapAnalyticsData($listings): array
    {
        $data = [];
        foreach ($listings as $listing) {
            $data[$listing->id] = array_merge(
                ['listing' => $listing],
                $this->metricsForListing($listing)
            );
        }

        return $data;
    }

    private function metricsForListing(Listing $listing): array
    {
        $spacesCount = (int) ($listing->spaces_count ?? 0);
        $bookedCount = (int) ($listing->booked_spaces_count ?? 0);
        $availableCount = max(0, $spacesCount - $bookedCount);

        $bookingStatus = match (true) {
            $spacesCount === 0 => 'No spaces',
            $bookedCount === 0 => 'All available',
            $bookedCount >= $spacesCount => 'Fully booked',
            default => 'Partially booked',
        };

        return [
            'total_views' => (int) ($listing->total_views_count ?? 0),
            'views_7d' => (int) ($listing->views_7d_count ?? 0),
            'views_30d' => (int) ($listing->views_30d_count ?? 0),
            'phone_clicks' => (int) ($listing->phone_clicks_count ?? 0),
            'phone_7d' => (int) ($listing->phone_7d_count ?? 0),
            'phone_30d' => (int) ($listing->phone_30d_count ?? 0),
            'whatsapp_clicks' => (int) ($listing->whatsapp_clicks_count ?? 0),
            'whatsapp_7d' => (int) ($listing->whatsapp_7d_count ?? 0),
            'whatsapp_30d' => (int) ($listing->whatsapp_30d_count ?? 0),
            'inquiries' => (int) ($listing->inquiries_count ?? 0),
            'spaces_count' => $spacesCount,
            'booked_spaces_count' => $bookedCount,
            'available_spaces_count' => $availableCount,
            'booking_status' => $bookingStatus,
        ];
    }
}
