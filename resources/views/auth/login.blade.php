@extends('layouts.auth-split')

@section('title', 'Sign In | GridSpace')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    .glass-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endpush

@section('content')
<main class="w-full min-h-screen flex flex-col md:flex-row overflow-hidden bg-white">
    <section class="hidden md:flex md:w-1/2 min-h-screen relative">
        <a href="{{ route('home') }}" class="absolute top-6 left-6 z-20 inline-flex items-center gap-2.5 rounded-xl bg-white/95 dark:bg-gray-900/95 px-4 py-2.5 shadow-md hover:shadow-lg transition-shadow" title="Back to GridSpace home">
            <img src="{{ asset('logo.jpeg') }}" alt="GridSpace" class="w-8 h-8 rounded-md object-contain">
            <span class="font-manrope text-lg font-extrabold text-[#0A2540] dark:text-white tracking-tight">GridSpace</span>
        </a>
        <img
            alt="Smiling professional in a workspace"
            class="absolute inset-0 w-full h-full object-cover"
            src="{{ asset('images/register-hero.png') }}"
        >
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute bottom-20 left-10 right-10 flex justify-center">
            <div class="glass-box p-8 rounded-2xl max-w-lg text-center text-white">
                <h1 class="text-3xl font-bold mb-4">
                    Join the future of <span class="text-[#F15A24]">flexible work</span>
                </h1>
                <p class="text-gray-100 text-lg">
                    Connect with thousands of verified workspaces and productive professionals
                </p>
            </div>
        </div>
    </section>

    <section class="w-full md:w-1/2 min-h-screen flex items-center justify-center p-6 sm:p-12 overflow-y-auto">
        <div class="w-full max-w-md border border-gray-100 dark:border-gray-700 rounded-3xl p-8 md:p-12 shadow-sm bg-white dark:bg-gray-900">
            <div class="flex justify-center mb-6">
                @include('auth.partials.logo-home-link', ['class' => 'mb-0'])
            </div>

            <header class="text-center mb-10">
                <h2 class="text-3xl font-bold text-[#0A2540] mb-2">Welcome Back!</h2>
                <p class="text-gray-500">Sign In to access your workspace dashboard</p>
            </header>

            @if($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-6" id="signin-form">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700" for="email">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-[#F15A24] focus:border-[#F15A24] placeholder-gray-400 @error('email') border-red-500 @enderror"
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="johndoe@gmail.com"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700" for="password">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input
                            class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-[#F15A24] focus:border-[#F15A24] placeholder-gray-400 @error('password') border-red-500 @enderror"
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Enter Password"
                            required
                            autocomplete="current-password"
                        >
                        <button class="absolute inset-y-0 right-0 flex items-center pr-3" type="button" data-toggle-password="password" aria-label="Toggle password visibility">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-[#F15A24] focus:ring-[#F15A24] border-gray-300 rounded" id="remember" name="remember" type="checkbox" @checked(old('remember'))>
                        <label class="ml-2 block text-sm text-gray-600" for="remember">Keep me logged in</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#F15A24] hover:underline">Forgot password?</a>
                    @endif
                </div>

                <div>
                    <button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-semibold text-white bg-[#F15A24] hover:bg-[#e04f1e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F15A24] transition-colors" type="submit">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="relative my-8">
                <div aria-hidden="true" class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <button type="button" class="flex items-center justify-center px-4 py-2 border border-slate-700 rounded-lg hover:bg-gray-50 transition-colors" disabled title="Coming soon">
                    <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    <span class="text-xs font-semibold hidden sm:inline">Google</span>
                </button>
                <button type="button" class="flex items-center justify-center px-4 py-2 border border-slate-700 rounded-lg hover:bg-gray-50 transition-colors" disabled title="Coming soon">
                    <svg class="h-5 w-5 mr-2 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span class="text-xs font-semibold text-slate-900 hidden sm:inline">Facebook</span>
                </button>
                <button type="button" class="flex items-center justify-center px-4 py-2 border border-slate-700 rounded-lg hover:bg-gray-50 transition-colors" disabled title="Coming soon">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.702z"/></svg>
                    <span class="text-xs font-semibold text-slate-900 hidden sm:inline">Apple</span>
                </button>
            </div>

            <footer class="mt-12 text-center text-sm text-gray-600">
                Don't have an account?
                <a class="text-[#F15A24] font-semibold hover:underline" href="{{ route('register') }}">Sign Up</a>
            </footer>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = document.getElementById(button.dataset.togglePassword);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
});
</script>
@endpush
