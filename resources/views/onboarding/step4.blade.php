@extends('layouts.onboarding')

@section('title', 'GridSpace Onboarding | Personalise Profile')

@section('content')
<header class="w-full h-20 bg-surface shadow-sm flex items-center px-4 md:px-12 justify-between fixed top-0 left-0 z-50">
    <a href="{{ route('home') }}" class="font-manrope text-xl font-bold text-on-surface">GridSpace</a>
    <span class="font-mono text-xs text-secondary uppercase tracking-widest">Step 4 / 4</span>
</header>

<main class="flex-grow flex items-center justify-center pt-28 pb-12 px-4 md:px-12">
    <div class="w-full max-w-[560px]">
        <div class="flex gap-2 mb-12">
            <div class="flex-1 progress-segment bg-primary-container"></div>
            <div class="flex-1 progress-segment bg-primary-container"></div>
            <div class="flex-1 progress-segment bg-primary-container"></div>
            <div class="flex-1 progress-segment bg-primary-container"></div>
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

        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 md:p-12 card-elevation">
            <div class="mb-12">
                <h1 class="font-manrope text-3xl font-bold text-on-surface mb-2">Personalise your profile</h1>
                <p class="text-secondary">Help us tailor your experience by providing a few more details about how you work.</p>
            </div>

            <form id="onboarding-step4-form" method="POST" action="{{ route('onboarding.step4.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="flex items-center gap-6 mb-8">
                    <label for="profile_photo" class="w-20 h-20 rounded-full border-2 border-dashed border-outline-variant flex items-center justify-center bg-surface-container-low overflow-hidden relative group cursor-pointer shrink-0">
                        @if($user->profile_photo_url)
                            <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-full h-full object-cover" id="profile-preview-img">
                        @else
                            <img
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCkFjc-3lhUcJA_8SXC2J60WlbIx2Bo-YwLfz3Ed24xRVWoQ-X4OVtHX8Bqa_FB-sx_HOYcGL-FTtjorjspKySZjIrc5gcJY_qmzLzpDxzss1FUuhwFsh44Z9jU8B6lkRETNHHAArcwWei_xe6OMTkFtZz0v8E_gu7sk0eOT6HZHdw7HjVZIyEbsIhtBDhcY2LGSkjYA_L-pyN3_v1bKNQwxZuwbBe65d8YsK8hyGIPNWMggPwjuPp9whFozuYn1gSO2stlcv5Zcu4"
                                alt="Profile placeholder"
                                class="w-full h-full object-cover opacity-20 group-hover:opacity-40 transition-opacity"
                                id="profile-preview-img"
                            >
                        @endif
                        <span class="material-symbols-outlined absolute text-on-surface-variant pointer-events-none">add_a_photo</span>
                    </label>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                    <div>
                        <span class="block font-manrope text-lg font-semibold text-on-surface">Profile Picture</span>
                        <span class="block font-mono text-xs text-secondary uppercase tracking-wide">Optional: Recommended 400x400px</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block font-mono text-xs uppercase text-secondary tracking-wider" for="professional_title">Professional Title</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary group-focus-within:text-primary transition-colors">work</span>
                        <input
                            class="w-full pl-12 pr-4 py-4 bg-white border border-outline-variant/50 rounded-lg focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all text-on-surface placeholder:text-gray-400 @error('professional_title') border-red-500 @enderror"
                            id="professional_title"
                            name="professional_title"
                            type="text"
                            value="{{ old('professional_title', $user->professional_title) }}"
                            placeholder="e.g. UX Designer, Full-stack Developer"
                            required
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block font-mono text-xs uppercase text-secondary tracking-wider" for="workspace_usage_frequency">Workspace usage frequency</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary group-focus-within:text-primary transition-colors z-10 pointer-events-none">calendar_month</span>
                        <select
                            class="custom-select w-full pl-12 pr-4 py-4 bg-white border border-outline-variant/50 rounded-lg focus:ring-2 focus:ring-primary-container focus:border-primary outline-none transition-all text-on-surface @error('workspace_usage_frequency') border-red-500 @enderror"
                            id="workspace_usage_frequency"
                            name="workspace_usage_frequency"
                            required
                        >
                            <option disabled @selected(!old('workspace_usage_frequency', $user->workspace_usage_frequency)) value="">Select frequency</option>
                            <option value="daily" @selected(old('workspace_usage_frequency', $user->workspace_usage_frequency) === 'daily')>Daily</option>
                            <option value="weekly" @selected(old('workspace_usage_frequency', $user->workspace_usage_frequency) === 'weekly')>Weekly</option>
                            <option value="monthly" @selected(old('workspace_usage_frequency', $user->workspace_usage_frequency) === 'monthly')>Monthly</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 mt-6 border-t border-outline-variant/20">
                    <a href="{{ route('onboarding.step3') }}" class="flex items-center gap-2 px-6 py-3 text-on-surface-variant hover:text-primary transition-colors group">
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:-translate-x-1">arrow_back</span>
                        Back
                    </a>
                    <button
                        id="continue-btn"
                        type="submit"
                        class="px-8 py-3 bg-primary-container text-white font-manrope font-semibold rounded-lg shadow-sm hover:bg-primary active:scale-95 transition-all flex items-center gap-2 opacity-50 cursor-not-allowed"
                        disabled
                    >
                        Complete Setup
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 font-mono text-xs text-secondary">
            Need help? <a class="text-primary hover:underline transition-all" href="{{ route('home') }}">Contact GridSpace support</a>
        </p>
    </div>
</main>

<footer class="w-full bg-surface-container-highest py-4 px-4 md:px-12">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center opacity-70">
        <span class="font-mono text-xs text-on-surface-variant">&copy; {{ date('Y') }} GridSpace. All rights reserved.</span>
        <div class="flex gap-6 mt-2 md:mt-0">
            <a class="font-mono text-xs text-on-surface-variant hover:text-primary transition-colors" href="#">Privacy Policy</a>
            <a class="font-mono text-xs text-on-surface-variant hover:text-primary transition-colors" href="#">Terms of Service</a>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('profile_photo');
    const photoLabel = document.querySelector('label[for="profile_photo"]');
    const previewImg = document.getElementById('profile-preview-img');
    const titleInput = document.getElementById('professional_title');
    const frequencySelect = document.getElementById('workspace_usage_frequency');
    const continueBtn = document.getElementById('continue-btn');

    photoLabel.addEventListener('click', function(e) {
        if (e.target !== photoInput) {
            e.preventDefault();
            photoInput.click();
        }
    });

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('opacity-20');
        };
        reader.readAsDataURL(file);
    });

    function validateForm() {
        const valid = titleInput.value.trim().length > 2 && frequencySelect.value !== '';
        continueBtn.disabled = !valid;
        continueBtn.classList.toggle('opacity-50', !valid);
        continueBtn.classList.toggle('cursor-not-allowed', !valid);
    }

    titleInput.addEventListener('input', validateForm);
    frequencySelect.addEventListener('change', validateForm);
    validateForm();
});
</script>
@endpush
