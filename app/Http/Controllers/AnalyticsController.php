<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index(Request $request)
    {
        $listings = Listing::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();

        $analyticsData = [];
        foreach ($listings as $listing) {
            $analyticsData[$listing->id] = [
                'listing' => $listing,
                'total_views' => $listing->analytics()->where('event_type', 'view')->count(),
                'unique_views' => $listing->analytics()
                    ->where('event_type', 'view')
                    ->distinct('ip_address')
                    ->count(),
                'phone_clicks' => $listing->analytics()->where('event_type', 'phone_click')->count(),
                'whatsapp_clicks' => $listing->analytics()->where('event_type', 'whatsapp_click')->count(),
                'inquiries' => $listing->analytics()->where('event_type', 'inquiry')->count(),
                'last_7_days' => $listing->analytics()
                    ->where('event_type', 'view')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
                'last_30_days' => $listing->analytics()
                    ->where('event_type', 'view')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count(),
            ];
        }

        return view('analytics.index', compact('analyticsData'));
    }

    /**
     * Export analytics data as CSV.
     */
    public function export(Request $request)
    {
        $listings = Listing::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();

        $csvData = [];
        $csvData[] = [
            'Listing Name',
            'Category',
            'Host',
            'Total Views',
            'Unique Views',
            'Phone Clicks',
            'WhatsApp Clicks',
            'Inquiries',
            'Last 7 Days Views',
            'Last 30 Days Views',
            'Created At'
        ];

        foreach ($listings as $listing) {
            $totalViews = $listing->analytics()->where('event_type', 'view')->count();
            $uniqueViews = $listing->analytics()
                ->where('event_type', 'view')
                ->distinct('ip_address')
                ->count();
            $phoneClicks = $listing->analytics()->where('event_type', 'phone_click')->count();
            $whatsappClicks = $listing->analytics()->where('event_type', 'whatsapp_click')->count();
            $inquiries = $listing->analytics()->where('event_type', 'inquiry')->count();
            $last7Days = $listing->analytics()
                ->where('event_type', 'view')
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
            $last30Days = $listing->analytics()
                ->where('event_type', 'view')
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            $csvData[] = [
                $listing->name,
                $listing->category->name,
                $listing->user->name,
                $totalViews,
                $uniqueViews,
                $phoneClicks,
                $whatsappClicks,
                $inquiries,
                $last7Days,
                $last30Days,
                $listing->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'gridspace-analytics-' . date('Y-m-d') . '.csv';

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream($callback, 200, $headers);
    }
}
