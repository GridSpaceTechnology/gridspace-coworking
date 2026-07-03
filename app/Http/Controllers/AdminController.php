<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\User;
use App\Models\FeatureRequest;
use App\Services\AdminBulkDeleteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private const PER_PAGE = 10;

    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $monthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $monthlyRevenue = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
            ->where('created_at', '>=', $monthStart)
            ->sum('total_price');

        $lastMonthRevenue = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_price');

        $revenueChange = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : ($monthlyRevenue > 0 ? 100 : 0);

        $stats = [
            'total_listings' => Listing::count(),
            'listings_today' => Listing::whereDate('created_at', today())->count(),
            'published_listings' => Listing::where('status', 'published')->count(),
            'featured_listings' => Listing::where('featured', true)->count(),
            'pending_listings' => Listing::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'total_hosts' => User::where('role', 'host')->count(),
            'total_inquiries' => \App\Models\Inquiry::count(),
            'total_bookings' => \App\Models\Booking::count(),
            'active_bookings' => \App\Models\Booking::whereIn('status', ['confirmed', 'pending'])->count(),
            'bookings_ending_today' => \App\Models\Booking::whereDate('check_out_date', today())->count(),
            'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
            'pending_featured_requests' => FeatureRequest::where('status', 'pending')->count(),
            'monthly_revenue' => $monthlyRevenue,
            'revenue_change' => $revenueChange,
        ];

        // Get featured listing requests from feature_requests table
        $featuredRequests = FeatureRequest::where('status', 'pending')
            ->with(['user', 'listing'])
            ->latest()
            ->get();

        $recentListings = Listing::with(['user', 'category', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentInquiries = \App\Models\Inquiry::with(['listing', 'listing.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentBookings = \App\Models\Booking::with(['listing', 'listing.user', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $featuredRequests = \App\Models\FeatureRequest::with(['user', 'listing', 'listing.images'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.index', compact('stats', 'recentListings', 'recentInquiries', 'recentBookings', 'featuredRequests'));
    }

    /**
     * Approve a feature request.
     */
    public function approveFeatureRequest(\App\Models\FeatureRequest $featureRequest)
    {
        $featureRequest->approve('Request approved by admin');

        return redirect()->back()->with('success', 'Featured request approved and listing is now featured!');
    }

    /**
     * Reject a feature request.
     */
    public function rejectFeatureRequest(\App\Models\FeatureRequest $featureRequest)
    {
        $featureRequest->reject('Request rejected by admin');

        return redirect()->back()->with('success', 'Featured request rejected.');
    }

    /**
     * Show users index.
     */
    public function usersIndex(Request $request)
    {
        $query = User::withCount(['listings', 'bookings']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('approved')) {
            $query->where('approved', $request->boolean('approved'));
        }

        $users = $query->latest()->paginate(self::PER_PAGE)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function bulkDeleteUsers(Request $request, AdminBulkDeleteService $bulkDelete): RedirectResponse
    {
        $ids = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ])['ids'];

        $count = $bulkDelete->deleteUsers($ids);

        return back()->with('success', "{$count} user(s) deleted successfully.");
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting admin users
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleUserStatus(User $user)
    {
        // Prevent disabling admin users
        if ($user->role === 'admin' && $user->id !== Auth::id()) {
            return redirect()->back()->with('error', 'Cannot change status of admin users.');
        }

        // Prevent disabling yourself
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot disable your own account.');
        }

        $user->approved = !$user->approved;
        $user->save();

        $status = $user->approved ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully.");
    }

    /**
     * Show all listings for admin management.
     */
    public function listingsIndex(Request $request)
    {
        $query = Listing::with(['user', 'category', 'city', 'images']);

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        $listings = $query->orderByDesc('created_at')->paginate(self::PER_PAGE)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.listings.index', compact('listings', 'categories'));
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

    /**
     * Show pending listings for admin approval.
     */
    public function pendingListings()
    {
        $pendingListings = Listing::with(['user', 'category', 'city'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pending-listings', compact('pendingListings'));
    }

    /**
     * Toggle featured status of a listing.
     */
    public function toggleFeatured(Request $request, Listing $listing)
    {
        $listing->featured = !$listing->featured;
        $listing->save();

        $status = $listing->featured ? 'featured' : 'unfeatured';

        return redirect()->back()
            ->with('success', "Listing {$status} successfully!");
    }

    /**
     * Approve a pending listing.
     */
    public function approveListing(Listing $listing)
    {
        $listing->status = 'published';
        $listing->save();

        return redirect()->back()
            ->with('success', 'Listing approved successfully!');
    }

    /**
     * Reject a pending listing.
     */
    public function rejectListing(Listing $listing)
    {
        $listing->status = 'draft';
        $listing->save();

        return redirect()->back()
            ->with('success', 'Listing rejected successfully!');
    }

    /**
     * Bulk approve all pending listings.
     */
    public function bulkApprove(Request $request)
    {
        $listingIds = $request->input('listings', []);

        foreach ($listingIds as $listingId) {
            $listing = Listing::find($listingId);
            if ($listing) {
                $listing->status = 'published';
                $listing->save();
            }
        }

        $count = count($listingIds);
        return redirect()->back()
            ->with('success', "{$count} listing(s) approved successfully!");
    }

    /**
     * Show all bookings.
     */
    public function indexBookings(Request $request)
    {
        $query = \App\Models\Booking::with(['listing', 'listing.user', 'user']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                    ->orWhere('guest_email', 'like', "%{$search}%")
                    ->orWhereHas('listing', fn ($l) => $l->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in_date', '<=', $request->date_to);
        }

        $bookings = $query->orderByDesc('created_at')->paginate(self::PER_PAGE)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function bulkDeleteBookings(Request $request, AdminBulkDeleteService $bulkDelete): RedirectResponse
    {
        $ids = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:bookings,id',
        ])['ids'];

        $count = $bulkDelete->deleteBookings($ids);

        return back()->with('success', "{$count} booking(s) deleted successfully.");
    }

    /**
     * Show booking details.
     */
    public function showBooking(\App\Models\Booking $booking)
    {
        $booking->load(['listing', 'listing.user', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update booking status.
     */
    public function updateBookingStatus(Request $request, \App\Models\Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', "Booking status updated to {$validated['status']} successfully!");
    }

    /**
     * Display all inquiries for admin management.
     */
    public function inquiriesIndex(Request $request)
    {
        $query = \App\Models\Inquiry::with(['listing', 'listing.user']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('listing', fn ($l) => $l->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('contacted')) {
            $query->where('contacted', $request->boolean('contacted'));
        }

        $inquiries = $query->orderByDesc('created_at')->paginate(self::PER_PAGE)->withQueryString();

        return view('admin.inquiries-index', compact('inquiries'));
    }

    public function bulkDeleteInquiries(Request $request, AdminBulkDeleteService $bulkDelete): RedirectResponse
    {
        $ids = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:inquiries,id',
        ])['ids'];

        $count = $bulkDelete->deleteInquiries($ids);

        return back()->with('success', "{$count} inquiry(ies) deleted successfully.");
    }

    /**
     * Toggle the contacted status of an inquiry.
     */
    public function toggleInquiryContacted(\App\Models\Inquiry $inquiry)
    {
        $inquiry->contacted = !$inquiry->contacted;
        $inquiry->save();

        return back()->with('success', 'Inquiry contact status updated.');
    }
}
