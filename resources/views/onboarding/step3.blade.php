@extends('layouts.auth-split')

@section('title', 'GridSpace Onboarding - Step 3 of 4')

@push('head')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'brand-navy': '#003366',
                    'brand-orange': '#f25a1d',
                    'light-gray-bg': '#f8f9fa',
                }
            }
        }
    }
</script>
<style>
    .onboarding-card { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); }
    .profile-placeholder { background-color: #e9ecef; }
</style>
@endpush

@section('content')
<div class="bg-light-gray-bg min-h-screen font-sans text-gray-800">
    <header class="pt-12 pb-8 text-center">
        <h1 class="text-4xl font-bold text-brand-navy mb-2">Welcome to GridSpace</h1>
        <p class="text-gray-500 text-lg">Let's personalise your workspace experience</p>
    </header>

    <div class="max-w-7xl mx-auto px-4">
        <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden mb-4">
            <div class="h-full w-3/4 bg-brand-navy rounded-full"></div>
        </div>
        <p class="text-center text-gray-400 text-sm mb-10">Step 3 of 4</p>
    </div>

    @if($errors->any())
        <div class="max-w-6xl mx-auto px-4 mb-6">
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 pb-20">
        <section class="bg-white rounded-lg onboarding-card p-12 md:p-20 relative min-h-[600px] flex flex-col items-center">
            <form
                id="onboarding-step3-form"
                method="POST"
                action="{{ route('onboarding.step3.store') }}"
                enctype="multipart/form-data"
                class="flex-grow flex flex-col items-center justify-center w-full"
            >
                @csrf

                <div class="flex flex-col items-center justify-center w-full max-w-2xl text-center">
                    <h2 class="text-3xl font-bold text-brand-navy mb-3">Add a profile picture</h2>
                    <p class="text-gray-500 mb-10">Help others recognise you (optional)</p>

                    <div class="flex flex-col items-center">
                        <div
                            id="profile-preview"
                            class="w-44 h-44 rounded-full profile-placeholder flex items-center justify-center mb-8 overflow-hidden bg-cover bg-center"
                            @if($user->profile_photo_url) style="background-image: url('{{ $user->profile_photo_url }}')" @endif
                        >
                            @unless($user->profile_photo_url)
                                <svg id="profile-placeholder-icon" class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            @endunless
                        </div>

                        <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">

                        <button
                            type="button"
                            id="choose-picture-button"
                            class="px-6 py-2 border-2 border-brand-orange text-brand-orange font-semibold rounded-lg hover:bg-orange-50 transition-colors"
                        >
                            Choose Picture
                        </button>
                    </div>

                    <p class="mt-12 text-gray-400 text-sm">
                        You can skip this step and add a profile photo later in your settings
                    </p>
                </div>

                <footer class="w-full flex justify-between items-center mt-20 pt-10">
                    <a href="{{ route('onboarding.step2') }}" class="flex items-center text-gray-900 font-semibold hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back
                    </a>

                    <div class="flex items-center gap-4">
                        <button
                            type="submit"
                            name="skip"
                            value="1"
                            class="text-gray-500 font-semibold hover:text-gray-700 transition-colors"
                        >
                            Skip
                        </button>
                        <button
                            type="submit"
                            class="bg-brand-orange text-white px-8 py-3 rounded-xl font-bold flex items-center hover:bg-orange-600 transition-colors shadow-lg shadow-orange-200"
                        >
                            Continue
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </footer>
            </form>
        </section>
    </main>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('profile_photo');
    const button = document.getElementById('choose-picture-button');
    const preview = document.getElementById('profile-preview');
    const icon = document.getElementById('profile-placeholder-icon');

    button.addEventListener('click', () => input.click());

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.style.backgroundImage = `url('${e.target.result}')`;
            if (icon) icon.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
