<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Breadcrumbs
{
    private static array $labels = [
        'home' => 'Home',
        'listings.index' => 'Find Space',
        'listings.show' => 'Listing',
        'listings.create' => 'Create Listing',
        'listings.edit' => 'Edit Listing',
        'blog.index' => 'Blog',
        'invest.index' => 'Investors',
        'featured' => 'Featured',
        'dashboard' => 'Dashboard',
        'bookings.index' => 'My Bookings',
        'bookings.create' => 'Book Workspace',
        'bookings.confirmation' => 'Confirmation',
        'inquiries.index' => 'Messages',
        'host.calendar' => 'Calendar',
        'host.earnings' => 'Earnings',
        'wallet.index' => 'Wallet',
        'profile.edit' => 'Profile',
        'admin.index' => 'Admin',
        'admin.listings.index' => 'Listings',
        'admin.users.index' => 'Users',
        'admin.bookings.index' => 'Bookings',
        'admin.blog.index' => 'Blog',
        'admin.blog.create' => 'Create Post',
        'admin.blog.edit' => 'Edit Post',
        'blog.show' => 'Article',
        'analytics.index' => 'Analytics',
        'admin.listings.index' => 'Listings',
        'admin.users.index' => 'Users',
        'admin.bookings.index' => 'Bookings',
        'admin.inquiries.index' => 'Inquiries',
        'feature-requests.index' => 'Feature Requests',
        'onboarding.step1' => 'Step 1 of 4',
        'onboarding.step2' => 'Step 2 of 4',
        'onboarding.step3' => 'Step 3 of 4',
        'onboarding.step4' => 'Step 4 of 4',
        'login' => 'Sign In',
        'register' => 'Create Account',
        'password.request' => 'Forgot Password',
        'password.reset' => 'Reset Password',
        'verification.notice' => 'Verify Email',
        'confirm-password' => 'Confirm Password',
    ];

    public static function resolve(?string $overrideLabel = null): array
    {
        $route = Route::current();
        $name = $route?->getName();
        $isAccount = self::isAccountArea($name);

        if (in_array($name, ['home', 'dashboard'], true)) {
            return [[
                'label' => $name === 'dashboard' ? 'Dashboard' : 'Home',
                'url' => null,
            ]];
        }

        if (in_array($name, ['login', 'register', 'password.request', 'password.reset'], true)) {
            return [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => self::$labels[$name] ?? self::humanizeRoute($name), 'url' => null],
            ];
        }

        if ($name && str_starts_with($name, 'onboarding.')) {
            $root = Auth::check()
                ? ['label' => 'Dashboard', 'url' => route('dashboard')]
                : ['label' => 'Home', 'url' => route('home')];

            return [
                $root,
                ['label' => 'Onboarding', 'url' => route('onboarding.step1')],
                ['label' => $overrideLabel ?? self::currentLabel($name, $route), 'url' => null],
            ];
        }

        $root = $isAccount
            ? ['label' => 'Dashboard', 'url' => route('dashboard')]
            : ['label' => 'Home', 'url' => route('home')];

        $currentLabel = $overrideLabel ?? self::currentLabel($name, $route);

        return [
            $root,
            ['label' => $currentLabel, 'url' => null],
        ];
    }

    public static function backUrl(): string
    {
        $previous = url()->previous();
        $current = url()->current();

        if ($previous && $previous !== $current) {
            return $previous;
        }

        return self::isAccountArea(Route::currentRouteName())
            ? route('dashboard')
            : route('home');
    }

    public static function hubUrl(): string
    {
        return Auth::check() ? route('dashboard') : route('home');
    }

    public static function shouldShowToolbar(): bool
    {
        if (\Illuminate\Support\Facades\View::hasSection('hide_page_toolbar')) {
            return false;
        }

        if (\Illuminate\Support\Facades\View::hasSection('force_page_toolbar')) {
            return true;
        }

        return request()->routeIs([
            'dashboard',
            'profile.edit',
            'admin.users.*',
        ]);
    }

    public static function isAccountArea(?string $name): bool
    {
        if (! Auth::check()) {
            return false;
        }

        if (\Illuminate\Support\Facades\View::hasSection('dashboard_nav')) {
            return true;
        }

        return $name && request()->routeIs([
            'dashboard',
            'bookings.*',
            'inquiries.*',
            'wallet.*',
            'host.*',
            'profile.*',
            'admin.*',
            'analytics.*',
            'listings.create',
            'listings.edit',
            'feature-requests.*',
            'onboarding.*',
        ]);
    }

    private static function currentLabel(?string $name, $route): string
    {
        if ($name === 'listings.show' && $route?->parameter('listing')) {
            return $route->parameter('listing')->name ?? 'Listing';
        }

        if ($name === 'bookings.create' && $route?->parameter('listing')) {
            return 'Book ' . ($route->parameter('listing')->name ?? 'Workspace');
        }

        return self::$labels[$name] ?? self::humanizeRoute($name);
    }

    private static function humanizeRoute(?string $name): string
    {
        if (! $name) {
            return 'Page';
        }

        $segment = str_contains($name, '.') ? strrchr($name, '.') : $name;
        $segment = ltrim((string) $segment, '.');

        return ucwords(str_replace(['-', '_'], ' ', $segment));
    }
}
