@extends('layouts.auth-split')

@section('title', 'GridSpace Onboarding - Step 1 of 4')

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
        width: 25%;
        height: 100%;
        background-color: #0A325E;
    }
    .selection-card {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .selection-card:hover {
        border-color: var(--brand-dark-blue);
        background-color: rgba(10, 50, 94, 0.02);
    }
    .selection-card:has(input:checked) {
        border-color: var(--brand-dark-blue);
        background-color: rgba(10, 50, 94, 0.04);
        box-shadow: 0 0 0 1px var(--brand-dark-blue);
    }
    .radio-circle {
        width: 24px;
        height: 24px;
        border: 1px solid #CBD5E1;
        border-radius: 50%;
        flex-shrink: 0;
        transition: all 0.2s ease-in-out;
    }
    .selection-card input:checked + .card-content .radio-circle {
        border-color: var(--brand-dark-blue);
        background-color: var(--brand-dark-blue);
        box-shadow: inset 0 0 0 4px white;
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
            <p class="text-center text-sm text-gray-500">Step 1 of 4</p>
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

        <article class="bg-white rounded-xl shadow-sm w-full max-w-5xl p-12 md:p-20 flex flex-col">
            <form id="onboarding-form" method="POST" action="{{ route('onboarding.step1.store') }}" class="flex flex-col">
                @csrf

                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-[#0A325E] mb-3">What brings you to GridSpace?</h2>
                    <p class="text-gray-500">Choose how you'd like to use the platform</p>
                </div>

                <div class="space-y-4 mb-12">
                    <label class="selection-card relative block border border-gray-200 rounded-xl p-6 md:p-8">
                        <input
                            class="sr-only peer"
                            name="user_intent"
                            type="radio"
                            value="search"
                            @checked($selectedIntent === 'search')
                            required
                        >
                        <div class="card-content flex items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 bg-[#0A325E] rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-[#0A325E]">I'm looking for spaces</h3>
                                    <p class="text-gray-500">Find and book workspaces for your needs</p>
                                </div>
                            </div>
                            <div class="radio-circle" aria-hidden="true"></div>
                        </div>
                    </label>

                    <label class="selection-card relative block border border-gray-200 rounded-xl p-6 md:p-8">
                        <input
                            class="sr-only peer"
                            name="user_intent"
                            type="radio"
                            value="host"
                            @checked($selectedIntent === 'host')
                        >
                        <div class="card-content flex items-center justify-between gap-4">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 bg-[#0A325E] rounded-lg flex items-center justify-center text-white shrink-0">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-[#0A325E]">I want to host spaces</h3>
                                    <p class="text-gray-500">List your workspace and earn income</p>
                                </div>
                            </div>
                            <div class="radio-circle" aria-hidden="true"></div>
                        </div>
                    </label>
                </div>

                <footer class="flex items-center justify-between mt-auto pt-4">
                    <a href="{{ route('home') }}" class="flex items-center text-black font-bold hover:text-gray-600 transition-colors">
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
                </footer>
            </form>
        </article>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.selection-card input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.selection-card').forEach(function(card) {
                card.setAttribute('aria-checked', 'false');
            });
            if (radio.checked) {
                radio.closest('.selection-card').setAttribute('aria-checked', 'true');
            }
        });
    });

    const checked = document.querySelector('.selection-card input[type="radio"]:checked');
    if (checked) {
        checked.closest('.selection-card').setAttribute('aria-checked', 'true');
    }
});
</script>
@endpush
