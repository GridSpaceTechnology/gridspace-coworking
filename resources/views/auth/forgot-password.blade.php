@extends('layouts.auth-split')

@section('title', 'Forgot Password - GridSpace')

@push('head')
<style>
    .text-brand-dark { color: #003366; }
    .text-brand-muted { color: #6B7280; }
    .bg-brand-orange { background-color: #F15A24; }
    .bg-brand-orange:hover { background-color: #D94E1B; }
</style>
@endpush

@section('content')
<div class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <main class="bg-white w-full max-w-5xl rounded-xl shadow-sm border border-gray-200 flex flex-col items-center justify-center p-8 md:p-16">
        <section class="text-center mb-8 max-w-md">
            <h1 class="text-3xl md:text-4xl font-bold text-brand-dark mb-4">
                Forgot Password?
            </h1>
            <p class="text-brand-muted text-lg leading-relaxed">
                Enter your email address below. We'll send you a verification code to reset your password
            </p>
        </section>

        @if(session('status'))
            <div class="w-full max-w-lg mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="w-full max-w-lg mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="w-full max-w-lg">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700" for="email">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-gray-900 placeholder-gray-400 sm:text-sm @error('email') border-red-500 @enderror"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>
                </div>

                <button class="w-full bg-brand-orange text-white font-semibold py-4 px-6 rounded-lg transition-colors duration-200 shadow-md text-lg" type="submit">
                    Submit
                </button>
            </form>
        </section>

        <footer class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Go back to
                <a class="text-orange-600 font-semibold underline underline-offset-4 hover:text-orange-700" href="{{ route('login') }}">
                    Sign In
                </a>
            </p>
        </footer>
    </main>
</div>
@endsection
