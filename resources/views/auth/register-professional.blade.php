@extends('layouts.auth-split')

@section('title', 'GridSpace - Create Account')

@section('content')
@php
    $fullName = trim(collect([old('firstname'), old('lastname')])->filter()->join(' '));
    $role = old('role', request('role', 'user'));
@endphp

<main class="flex min-h-screen flex-col lg:flex-row">
    <section class="relative hidden lg:flex lg:w-1/2 xl:w-[55%] items-end justify-center overflow-hidden">
        <a href="{{ route('home') }}" class="absolute top-6 left-6 z-20 inline-flex items-center gap-2.5 rounded-xl bg-white/95 dark:bg-gray-900/95 px-4 py-2.5 shadow-md hover:shadow-lg transition-shadow" title="Back to GridSpace home">
            <img src="{{ asset('logo.jpeg') }}" alt="GridSpace" class="w-8 h-8 rounded-md object-contain">
            <span class="font-manrope text-lg font-extrabold text-[#0A2540] dark:text-white tracking-tight">GridSpace</span>
        </a>
        <img
            alt="Professional woman in a modern workspace"
            class="absolute inset-0 h-full w-full object-cover"
            src="{{ asset('images/register-hero.png') }}"
        >
        <div class="relative z-10 w-full max-w-lg px-8 pb-12">
            <div class="rounded-2xl bg-black/40 backdrop-blur-sm border border-white/20 px-8 py-8 text-center">
                <h2 class="text-3xl font-extrabold text-white leading-tight mb-3">
                    Join the future of <span class="text-brand-orange">flexible work</span>
                </h2>
                <p class="text-white/90 text-sm leading-relaxed">
                    Connect with thousands of verified workspaces and productive professionals
                </p>
            </div>
        </div>
    </section>

    <section class="flex flex-1 items-center justify-center p-6 sm:p-12 lg:w-1/2 xl:w-[45%] bg-white dark:bg-gray-900">
        <div class="w-full max-w-md">
            <div class="flex justify-center mb-6">
                @include('auth.partials.logo-home-link', ['class' => 'mb-0'])
            </div>

            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-brand-blue">Create your GridSpace Account</h2>
                <p class="text-gray-500 mt-2">Join thousands of professionals finding flexible workspaces</p>
            </div>

            @if($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5" id="registration-form">
                @csrf
                <input type="hidden" name="firstname" id="firstname" value="{{ old('firstname') }}">
                <input type="hidden" name="lastname" id="lastname" value="{{ old('lastname') }}">
                <input type="hidden" name="location" value="{{ old('location', 'Nigeria') }}">
                <input type="hidden" name="role" value="{{ $role }}">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="full-name">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-brand-orange focus:border-brand-orange text-sm @error('firstname') border-red-500 @enderror"
                            id="full-name"
                            type="text"
                            value="{{ $fullName }}"
                            placeholder="John Doe"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="email">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-brand-orange focus:border-brand-orange text-sm @error('email') border-red-500 @enderror"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="johndoe@gmail.com"
                            required
                            autocomplete="username"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="phone">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-brand-orange focus:border-brand-orange text-sm @error('phone') border-red-500 @enderror"
                            id="phone"
                            name="phone"
                            type="tel"
                            value="{{ old('phone') }}"
                            placeholder="09087689756"
                            required
                            autocomplete="tel"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="password">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-brand-orange focus:border-brand-orange text-sm @error('password') border-red-500 @enderror"
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Enter Password"
                            required
                            autocomplete="new-password"
                        >
                        <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" type="button" data-toggle-password="password" aria-label="Toggle password visibility">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="password_confirmation">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-brand-orange focus:border-brand-orange text-sm"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Confirm Password"
                            required
                            autocomplete="new-password"
                        >
                        <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" type="button" data-toggle-password="password_confirmation" aria-label="Toggle confirm password visibility">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                @if($role === 'host')
                    <p class="text-sm text-brand-orange font-semibold">You're signing up as a workspace host.</p>
                @endif

                <button class="w-full bg-brand-orange text-white py-3.5 rounded-lg font-bold text-lg hover:opacity-90 transition-opacity" type="submit">
                    Sign Up
                </button>

                <div class="flex items-start text-sm">
                    <input class="h-4 w-4 mt-0.5 text-brand-orange focus:ring-brand-orange border-gray-300 rounded" id="terms" name="terms" type="checkbox" required>
                    <label class="ml-2 block text-gray-600" for="terms">
                        I agree to <a class="text-blue-600 hover:underline" href="#">Terms &amp; Conditions</a> and <a class="text-blue-600 hover:underline" href="#">Privacy Policy</a>
                    </label>
                </div>
            </form>

            <div class="relative my-8">
                <div aria-hidden="true" class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <button type="button" class="flex items-center justify-center py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" disabled title="Coming soon">
                    <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    <span class="ml-2 text-sm font-medium hidden sm:inline">Google</span>
                </button>
                <button type="button" class="flex items-center justify-center py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" disabled title="Coming soon">
                    <svg class="h-5 w-5 text-blue-600 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span class="ml-2 text-sm font-medium hidden sm:inline">Facebook</span>
                </button>
                <button type="button" class="flex items-center justify-center py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" disabled title="Coming soon">
                    <svg class="h-5 w-5 text-black fill-current" viewBox="0 0 24 24"><path d="M17.073 21.326c-1.007 0-2.483-.604-3.568-.604-1.077 0-2.316.591-3.23.591-2.457 0-5.187-2.613-5.187-6.241 0-3.504 2.213-5.385 4.343-5.385 1.054 0 1.942.545 2.92.545.918 0 1.748-.545 2.946-.545 1.724 0 3.32 1.01 4.14 2.128-3.324 1.413-2.784 5.94.595 7.151-.734 1.542-1.636 2.36-2.959 2.36zm-3.805-15.658c-.015-2.222 1.848-4.048 4.01-4.06.035 2.378-1.996 4.154-4.01 4.06z"/></svg>
                    <span class="ml-2 text-sm font-medium hidden sm:inline">Apple</span>
                </button>
            </div>

            <p class="mt-12 text-center text-sm text-gray-600">
                Already have an account?
                <a class="text-brand-orange font-semibold hover:underline" href="{{ route('login') }}">Sign In</a>
            </p>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    const fullNameInput = document.getElementById('full-name');
    const firstnameInput = document.getElementById('firstname');
    const lastnameInput = document.getElementById('lastname');

    form.addEventListener('submit', function() {
        const parts = fullNameInput.value.trim().split(/\s+/).filter(Boolean);
        firstnameInput.value = parts[0] || '';
        lastnameInput.value = parts.slice(1).join(' ') || parts[0] || 'User';
    });

    document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = document.getElementById(button.dataset.togglePassword);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
});
</script>
@endpush
