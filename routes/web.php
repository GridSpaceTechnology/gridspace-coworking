<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\FeaturedRequestController;
use App\Http\Controllers\FeatureRequestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\HostController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/invest', [InvestorController::class, 'index'])->name('invest.index');
Route::post('/invest', [InvestorController::class, 'store'])->name('invest.store');
Route::get('/featured', [FeaturedController::class, 'index'])->name('featured');
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware('auth');
Route::get('/listings/{listing:slug}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/track/{listing}/{type}', [ListingController::class, 'track'])->name('track')->where('listing', '[0-9]+');
Route::post('/inquiries/store', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/bookings/create/{listing:slug}/{space}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings/store/{listing:slug}/{space}', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');

// Feature Request routes
Route::middleware('auth')->group(function () {
    Route::get('/feature-requests/create/{listing}', [FeatureRequestController::class, 'create'])->name('feature-requests.create')->where('listing', '[0-9]+');
    Route::post('/feature-requests/store/{listing}', [FeatureRequestController::class, 'store'])->name('feature-requests.store');
    Route::get('/feature-requests', [FeatureRequestController::class, 'index'])->name('feature-requests.index');
    Route::post('/feature-requests/{featureRequest}/approve', [FeatureRequestController::class, 'approve'])->name('feature-requests.approve');
    Route::post('/feature-requests/{featureRequest}/reject', [FeatureRequestController::class, 'reject'])->name('feature-requests.reject');
});

// Host routes
Route::middleware('auth')->group(function () {
    Route::get('/onboarding/step-1', [OnboardingController::class, 'step1'])->name('onboarding.step1');
    Route::post('/onboarding/step-1', [OnboardingController::class, 'storeStep1'])->name('onboarding.step1.store');
    Route::get('/onboarding/step-2', [OnboardingController::class, 'step2'])->name('onboarding.step2');
    Route::post('/onboarding/step-2', [OnboardingController::class, 'storeStep2'])->name('onboarding.step2.store');
    Route::get('/onboarding/step-3', [OnboardingController::class, 'step3'])->name('onboarding.step3');
    Route::post('/onboarding/step-3', [OnboardingController::class, 'storeStep3'])->name('onboarding.step3.store');
    Route::get('/onboarding/step-4', [OnboardingController::class, 'step4'])->name('onboarding.step4');
    Route::post('/onboarding/step-4', [OnboardingController::class, 'storeStep4'])->name('onboarding.step4.store');

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
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}/contacted', [InquiryController::class, 'toggleContacted'])->name('inquiries.toggle-contacted');
    Route::post('/inquiries/{inquiry}/messages', [InquiryController::class, 'storeMessage'])->name('inquiries.messages.store');
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    Route::prefix('host')->name('host.')->group(function () {
        Route::get('/calendar', [HostController::class, 'calendar'])->name('calendar');
        Route::get('/earnings', [HostController::class, 'earnings'])->name('earnings');
    });

    // Listing management routes - require auth for create, host/admin for other actions
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/admin/listings/pending', [AdminController::class, 'pendingListings'])->name('admin.listings.pending');
    Route::post('/admin/listings/bulk-approve', [AdminController::class, 'bulkApprove'])->name('admin.bulk-approve');
    Route::get('/listings/{listing:slug}/edit', [ListingController::class, 'edit'])->name('listings.edit');
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
    Route::get('/admin/blog', [AdminBlogController::class, 'index'])->name('admin.blog.index');
    Route::get('/admin/blog/create', [AdminBlogController::class, 'create'])->name('admin.blog.create');
    Route::post('/admin/blog', [AdminBlogController::class, 'store'])->name('admin.blog.store');
    Route::get('/admin/blog/{post:id}/edit', [AdminBlogController::class, 'edit'])->name('admin.blog.edit');
    Route::put('/admin/blog/{post:id}', [AdminBlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('/admin/blog/{post:id}', [AdminBlogController::class, 'destroy'])->name('admin.blog.destroy');
    Route::post('/admin/blog/bulk-delete', [AdminBlogController::class, 'bulkDestroy'])->name('admin.blog.bulk-delete');
    Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/admin/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    Route::post('/admin/analytics/bulk-delete', [AnalyticsController::class, 'bulkDeleteListings'])->name('admin.analytics.bulk-delete');
    Route::get('/admin/listings', [AdminController::class, 'listingsIndex'])->name('admin.listings.index');
    Route::post('/admin/listings/bulk-delete', [AdminController::class, 'bulkDeleteListings'])->name('admin.listings.bulk-delete');
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::post('/admin/users/bulk-delete', [AdminController::class, 'bulkDeleteUsers'])->name('admin.users.bulk-delete');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::patch('/admin/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    Route::patch('/admin/listings/{listing}/featured', [AdminController::class, 'toggleFeatured'])->name('admin.toggle-featured');
    Route::post('/admin/listings/{listing:slug}/approve', [AdminController::class, 'approveListing'])->name('admin.listings.approve');
    Route::post('/admin/listings/{listing:slug}/reject', [AdminController::class, 'rejectListing'])->name('admin.listings.reject');
    Route::get('/admin/bookings', [AdminController::class, 'indexBookings'])->name('admin.bookings.index');
    Route::post('/admin/bookings/bulk-delete', [AdminController::class, 'bulkDeleteBookings'])->name('admin.bookings.bulk-delete');
    Route::get('/admin/bookings/{booking}', [AdminController::class, 'showBooking'])->name('admin.bookings.show');
    Route::patch('/admin/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.update-status');
    Route::get('/admin/inquiries', [AdminController::class, 'inquiriesIndex'])->name('admin.inquiries.index');
    Route::post('/admin/inquiries/bulk-delete', [AdminController::class, 'bulkDeleteInquiries'])->name('admin.inquiries.bulk-delete');
    Route::patch('/admin/inquiries/{inquiry}/toggle-contacted', [AdminController::class, 'toggleInquiryContacted'])->name('admin.inquiries.toggle-contacted');
    Route::get('/admin/listings/pending', [AdminController::class, 'pendingListings'])->name('admin.listings.pending');
    Route::get('/admin/featured-requests', [FeaturedRequestController::class, 'index'])->name('admin.featured-requests.index');
    Route::post('/admin/featured-requests/{listing}/approve', [FeaturedRequestController::class, 'approve'])->name('admin.featured-requests.approve');
    Route::post('/admin/featured-requests/{listing}/reject', [FeaturedRequestController::class, 'reject'])->name('admin.featured-requests.reject');
    Route::post('/admin/feature-requests/{featureRequest}/approve', [AdminController::class, 'approveFeatureRequest'])->name('admin.feature-requests.approve');
    Route::post('/admin/feature-requests/{featureRequest}/reject', [AdminController::class, 'rejectFeatureRequest'])->name('admin.feature-requests.reject');
});

require __DIR__.'/auth.php';
