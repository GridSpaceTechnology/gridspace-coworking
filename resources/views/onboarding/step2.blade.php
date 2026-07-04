@extends('layouts.auth-split')

@section('title', 'GridSpace Onboarding - Step 2 of 4')

@push('head')
<style>
    :root {
        --brand-dark-blue: #0A325E;
        --brand-orange: #F15A24;
    }
    .progress-bar-container {
        height: 6px;
        background-color: #E5E7EB;
        border-radius: 999px;
        overflow: hidden;
    }
    .progress-bar-fill {
        width: 50%;
        height: 100%;
        background-color: #0A325E;
    }
    .location-field:focus-within {
        border-color: var(--brand-dark-blue);
        box-shadow: 0 0 0 1px var(--brand-dark-blue);
    }
</style>
@endpush

@section('content')
<div class="bg-[#F5F5F5] min-h-screen flex items-center justify-center p-4 font-sans text-[#1F2937]">
    <main class="w-full max-w-5xl flex flex-col items-center">
        <section class="text-center mb-8">
            <h1 class="text-4xl font-bold text-[#0A325E] mb-2">Welcome to GridSpace</h1>
            <p class="text-lg text-gray-500">Let's personalise your workspace experience</p>
        </section>

        <div class="w-full max-w-4xl mb-6">
            <div class="progress-bar-container mb-2">
                <div class="progress-bar-fill"></div>
            </div>
            <p class="text-center text-sm text-gray-500">Step 2 of 4</p>
        </div>

        @if($errors->any())
            <div class="w-full max-w-5xl mb-6">
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <article class="bg-white rounded-xl shadow-sm w-full max-w-5xl p-12 md:p-20 flex flex-col items-center relative">
            <form method="POST" action="{{ route('onboarding.step2.store') }}" id="onboarding-step2-form" class="w-full flex flex-col items-center">
                @csrf

                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-[#0A325E] mb-3">Where are you based?</h2>
                    <p class="text-gray-500">Select your state, then choose your city to see relevant workspaces</p>
                </div>

                <div class="w-full max-w-2xl space-y-5 mb-16">
                    <div>
                        <label for="state-select" class="block text-sm font-semibold text-[#0A325E] mb-2">State</label>
                        <div class="location-field flex items-center border border-gray-300 rounded-lg px-5 py-4 transition-all duration-200">
                            <span class="mr-3 text-gray-400 shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </span>
                            <select
                                id="state-select"
                                name="state"
                                required
                                class="w-full border-none focus:ring-0 text-lg bg-transparent p-0 @error('state') text-red-600 @enderror"
                            >
                                <option value="">Select your state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}" @selected($selectedState === $state)>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="city-select" class="block text-sm font-semibold text-[#0A325E] mb-2">City</label>
                        <div class="location-field flex items-center border border-gray-300 rounded-lg px-5 py-4 transition-all duration-200">
                            <span class="mr-3 text-gray-400 shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <select
                                id="city-select"
                                name="city"
                                required
                                @disabled(! $selectedState)
                                class="w-full border-none focus:ring-0 text-lg bg-transparent p-0 disabled:text-gray-400 @error('city') text-red-600 @enderror"
                            >
                                <option value="">{{ $selectedState ? 'Select your city' : 'Select a state first' }}</option>
                            </select>
                        </div>
                        <p id="city-hint" class="mt-2 text-sm text-gray-500 {{ $selectedState ? 'hidden' : '' }}">
                            Cities will appear after you choose a state.
                        </p>
                    </div>
                </div>

                <div class="w-full flex justify-between items-center mt-auto">
                    <a href="{{ route('onboarding.step1') }}" class="flex items-center text-black font-bold hover:text-gray-600 transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                    <button type="submit" class="bg-[#F15A24] hover:bg-[#d94e1e] text-white px-8 py-3 rounded-lg font-bold flex items-center transition-all shadow-md">
                        Continue
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </article>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const citiesByState = @json($citiesByState);
    const stateSelect = document.getElementById('state-select');
    const citySelect = document.getElementById('city-select');
    const cityHint = document.getElementById('city-hint');
    const initialCity = @json($selectedCity);

    function populateCities(state, selectedCity = '') {
        const cities = citiesByState[state] || [];

        citySelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = cities.length ? 'Select your city' : 'No cities available';
        citySelect.appendChild(placeholder);

        cities.forEach(function (city) {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            if (city === selectedCity) {
                option.selected = true;
            }
            citySelect.appendChild(option);
        });

        citySelect.disabled = cities.length === 0;
        cityHint.classList.toggle('hidden', Boolean(state));
    }

    stateSelect.addEventListener('change', function () {
        populateCities(stateSelect.value);
    });

    if (stateSelect.value) {
        populateCities(stateSelect.value, initialCity);
    }
});
</script>
@endpush
