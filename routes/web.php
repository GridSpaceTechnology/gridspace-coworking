<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/featured', [FeaturedController::class, 'index'])->name('featured');
Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create')->middleware(['auth', 'host']);
Route::get('/listings/{listing:slug}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/listings/{listing:slug}/book', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/track/{listing}/{type}', [ListingController::class, 'track'])->name('track');

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Host routes
    Route::middleware('host')->group(function () {
        Route::get('/dashboard', [ListingController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');

        // Listing management routes
        Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
        Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
        Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
        Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    });

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/admin/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
        Route::patch('/admin/listings/{listing}/featured', [AdminController::class, 'toggleFeatured'])->name('admin.toggle-featured');
    });
});

require __DIR__.'/auth.php';
