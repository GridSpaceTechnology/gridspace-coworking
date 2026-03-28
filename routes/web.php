<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\FeaturedRequestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HostApprovalController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/featured', [FeaturedController::class, 'index'])->name('featured');
    Route::get('/featured/request', [FeaturedRequestController::class, 'create'])->name('featured-requests.create');
    Route::post('/featured/request', [FeaturedRequestController::class, 'store'])->name('featured-requests.store');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware('auth');
Route::get('/listings/{listing:slug}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/track/{listing}/{type}', [ListingController::class, 'track'])->name('track')->where('listing', '[0-9]+');
Route::get('/listings/{listing:slug}/book', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings/{listing:slug}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');

    // Debug route - remove after fixing
    Route::get('/debug-user', function() {
        if (Auth::check()) {
            $user = Auth::user();
            return [
                'email' => $user->email,
                'role' => $user->role,
                'isHost' => $user->isHost(),
                'isAdmin' => $user->isAdmin(),
                'isApproved' => $user->isApproved(),
            ];
        }
        return ['error' => 'Not authenticated'];
    })->name('debug.user');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/my-inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Listing management routes - require auth for create, host/admin for other actions
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/admin/listings/pending', [AdminController::class, 'pendingListings'])->name('admin.listings.pending');
    Route::post('/admin/listings/bulk-approve', [AdminController::class, 'bulkApprove'])->name('admin.bulk-approve');
    Route::post('/admin/listings/{listing:slug}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing:slug}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing:slug}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::get('/admin/listings-approval', [AdminController::class, 'listingsApproval'])->name('admin.listings.approval');
    Route::post('/admin/listings/bulk-approve', [AdminController::class, 'bulkApprove'])->name('admin.bulk-approve');
    Route::post('/admin/listings/{listing:slug}/approve', [AdminController::class, 'approveListing'])->name('admin.listings.approve');
    Route::post('/admin/listings/{listing:slug}/reject', [AdminController::class, 'rejectListing'])->name('admin.listings.reject');
});

// Admin routes
Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/admin/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    Route::patch('/admin/listings/{listing}/featured', [AdminController::class, 'toggleFeatured'])->name('admin.toggle-featured');
    Route::post('/admin/listings/{listing:slug}/approve', [AdminController::class, 'approveListing'])->name('admin.listings.approve');
    Route::post('/admin/listings/{listing:slug}/reject', [AdminController::class, 'rejectListing'])->name('admin.listings.reject');
    Route::get('/admin/bookings', [AdminController::class, 'indexBookings'])->name('admin.bookings.index');
    Route::get('/admin/bookings/{booking}', [AdminController::class, 'showBooking'])->name('admin.bookings.show');
    Route::patch('/admin/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.update-status');
    Route::get('/admin/host-approval', [HostApprovalController::class, 'index'])->name('admin.hosts.approval');
    Route::post('/admin/host-approval/{user}/approve', [HostApprovalController::class, 'approve'])->name('admin.hosts.approve');
    Route::post('/admin/host-approval/{user}/reject', [HostApprovalController::class, 'reject'])->name('admin.hosts.reject');
    Route::get('/admin/listings/pending', [AdminController::class, 'pendingListings'])->name('admin.listings.pending');
    Route::get('/admin/featured-requests', [FeaturedRequestController::class, 'index'])->name('admin.featured-requests.index');
    Route::post('/admin/featured-requests/{listing}/approve', [FeaturedRequestController::class, 'approve'])->name('admin.featured-requests.approve');
    Route::post('/admin/featured-requests/{listing}/reject', [FeaturedRequestController::class, 'reject'])->name('admin.featured-requests.reject');
});

require __DIR__.'/auth.php';
