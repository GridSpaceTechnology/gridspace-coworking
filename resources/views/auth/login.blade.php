<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen">

        <!-- Logo -->
        <div class="mr-6 flex items-start">
            <img src="{{ asset('logo.jpeg') }}"
                 alt="Gridspace Cowork"
                 class="h-28 w-56 object-contain">
        </div>

        <!-- Form -->
        <div class="w-96 pt-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email Address"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a
                            class="underline text-sm text-gray-600 hover:text-gray-900"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <a href="{{ route('register') }}"
                       class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        {{ __('Create an account') }}
                    </a>

                    <x-primary-button class="ml-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>
