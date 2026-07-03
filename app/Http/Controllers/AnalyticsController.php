<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingAnalytic;
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
            'total_views' => ListingAnalytic::where('event_type', 'view')->count(),
            'unique_views' => ListingAnalytic::where('event_type', 'view')->distinct('ip_address')->count('ip_address'),
            'total_inquiries' => ListingAnalytic::where('event_type', 'inquiry')->count(),
        ];

        $categories = Category::orderBy('name')->get();

        return view('admin.analytics.index', compact('analyticsData', 'listings', 'summary', 'categories'));
    }

    public function export(Request $request)
    {
        $listings = $this->buildListingQuery($request)->get();

        $csvData = [[
            'Listing Name', 'Category', 'Host', 'Total Views', 'Unique Views',
            'Phone Clicks', 'WhatsApp Clicks', 'Inquiries', 'Last 7 Days Views',
            'Last 30 Days Views', 'Created At',
        ]];

        foreach ($listings as $listing) {
            $metrics = $this->metricsForListing($listing);
            $csvData[] = [
                $listing->name,
                $listing->category?->name ?? '',
                $listing->user?->name ?? '',
                $metrics['total_views'],
                $metrics['unique_views'],
                $metrics['phone_clicks'],
                $metrics['whatsapp_clicks'],
                $metrics['inquiries'],
                $metrics['last_7_days'],
                $metrics['last_30_days'],
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
                'analytics as last_7_days_count' => fn ($q) => $q->where('event_type', 'view')->where('created_at', '>=', now()->subDays(7)),
                'analytics as last_30_days_count' => fn ($q) => $q->where('event_type', 'view')->where('created_at', '>=', now()->subDays(30)),
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

        return match ($request->input('sort', 'created_desc')) {
            'views_desc' => $query->orderByDesc('total_views_count'),
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
        return [
            'total_views' => $listing->total_views_count ?? 0,
            'unique_views' => $listing->analytics()
                ->where('event_type', 'view')
                ->distinct('ip_address')
                ->count('ip_address'),
            'phone_clicks' => $listing->phone_clicks_count ?? 0,
            'whatsapp_clicks' => $listing->whatsapp_clicks_count ?? 0,
            'inquiries' => $listing->inquiries_count ?? 0,
            'last_7_days' => $listing->last_7_days_count ?? 0,
            'last_30_days' => $listing->last_30_days_count ?? 0,
        ];
    }
}
