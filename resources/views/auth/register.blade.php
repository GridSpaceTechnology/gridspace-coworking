<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="firstname" :value="__('First Name')" />
                <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="lastname" :value="__('Last Name')" />
                <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ old('gender') }}</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Marital Status -->
        <div class="mt-4">
            <x-input-label for="marital_status" :value="__('Marital Status')" />
            <select id="marital_status" name="marital_status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ old('marital_status') }}</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="divorced">Divorced</option>
                <option value="widowed">Widowed</option>
            </select>
            <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
        </div>

        <!-- Date of Birth -->
        <div class="mt-4">
            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" />
            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>

        <!-- Residence -->
        <div class="mt-4">
            <x-input-label for="residence" :value="__('Residence')" />
            <x-text-input id="residence" class="block mt-1 w-full" type="text" name="residence" :value="old('residence')" />
            <x-input-error :messages="$errors->get('residence')" class="mt-2" />
        </div>

        <!-- Local Government Area -->
        <div class="mt-4">
            <x-input-label for="local_government_area" :value="__('Local Government Area')" />
            <x-text-input id="local_government_area" class="block mt-1 w-full" type="text" name="local_government_area" :value="old('local_government_area')" />
            <x-input-error :messages="$errors->get('local_government_area')" class="mt-2" />
        </div>

        <!-- State of Origin -->
        <div class="mt-4">
            <x-input-label for="state_of_origin" :value="__('State of Origin')" />
            <x-text-input id="state_of_origin" class="block mt-1 w-full" type="text" name="state_of_origin" :value="old('state_of_origin')" />
            <x-input-error :messages="$errors->get('state_of_origin')" class="mt-2" />
        </div>

        <!-- Home Town -->
        <div class="mt-4">
            <x-input-label for="home_town" :value="__('Home Town')" />
            <x-text-input id="home_town" class="block mt-1 w-full" type="text" name="home_town" :value="old('home_town')" />
            <x-input-error :messages="$errors->get('home_town')" class="mt-2" />
        </div>

        <!-- Nationality -->
        <div class="mt-4">
            <x-input-label for="nationality" :value="__('Nationality')" />
            <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality" :value="old('nationality')" />
            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
        </div>

        <!-- Religion -->
        <div class="mt-4">
            <x-input-label for="religion" :value="__('Religion')" />
            <x-text-input id="religion" class="block mt-1 w-full" type="text" name="religion" :value="old('religion')" />
            <x-input-error :messages="$errors->get('religion')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
