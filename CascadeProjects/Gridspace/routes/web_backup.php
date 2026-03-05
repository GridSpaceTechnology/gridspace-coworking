<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [ListingController::class, 'index'])->name('home');
Route::get('/listings/{listing:slug}', [ListingController::class, 'show'])->name('listings.show');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
Route::get('/track/{listing}/{type}', [ListingController::class, 'track'])->name('track');

// Test simple route
Route::get('/test-create', [ListingController::class, 'create']);

// Test with auth middleware only
Route::get('/test-create-auth', [ListingController::class, 'create'])->middleware('auth');

// Test with host middleware
Route::get('/test-create-host', [ListingController::class, 'create'])->middleware('host');

// Test the actual route path outside groups
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('host')->name('listings.create');

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Host routes
    Route::middleware('host')->group(function () {
        Route::get('/dashboard', [ListingController::class, 'dashboard'])->name('dashboard');
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
