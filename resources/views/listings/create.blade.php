@extends('layouts.host')

@section('title', 'Add New Listing | GridSpace')

@push('head')
<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    .step-pill.done { background-color: #ff5a1f; color: #fff; }
    .step-pill.active { background-color: #1c2c40; color: #fff; }
    .amenity-card input:checked + .amenity-inner {
        border-color: #ff5a1f;
        background-color: #fff5f0;
    }
    .amenity-card input:checked + .amenity-inner .amenity-check {
        opacity: 1;
    }
</style>
@endpush

@section('host_content')
<section class="mb-6">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Add New Listing</h1>
    <p class="font-inter text-sm text-on-surface-variant mt-1">Complete each step to publish your workspace</p>
</section>

<div class="bg-white border border-outline-variant/60 rounded-2xl p-6 md:p-8 card-lift max-w-4xl mx-auto">
    {{-- Progress --}}
    <div class="flex items-center justify-between mb-8 overflow-x-auto gap-2 pb-2" id="wizard-progress">
        @foreach(['Basic Info', 'Amenities', 'Photos', 'Pricing', 'Review'] as $i => $label)
            <div class="flex items-center gap-2 shrink-0">
                <div class="step-pill w-8 h-8 rounded-full flex items-center justify-center font-inter text-xs font-bold bg-surface-container text-on-surface-variant transition-colors"
                     data-step-indicator="{{ $i + 1 }}">{{ $i + 1 }}</div>
                <span class="font-inter text-xs font-medium text-on-surface-variant hidden sm:inline step-label" data-step-label="{{ $i + 1 }}">{{ $label }}</span>
                @if($i < 4)
                    <div class="w-6 md:w-10 h-0.5 bg-outline-variant/50 hidden sm:block"></div>
                @endif
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" id="listing-wizard-form">
        @csrf
        <input type="hidden" name="price_period" value="night">

        {{-- Step 1: Basic Information --}}
        <div class="wizard-step active" data-step="1">
            <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-6">Basic Information</h2>
            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Workspace Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}" data-wizard-required
                               class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Workspace Type *</label>
                        <select name="category_id" required data-wizard-required
                                class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                            <option value="">Select type</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Full Address *</label>
                    <input type="text" name="address" required value="{{ old('address') }}" data-wizard-required
                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Location</label>
                        <select name="city_id"
                                class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                            <option value="">Select city</option>
                            @foreach($cities->groupBy('state') as $state => $stateCities)
                                @if($state)
                                    <optgroup label="{{ $state }}">
                                        @foreach($stateCities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    @foreach($stateCities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Capacity</label>
                        <input type="number" name="capacity" min="1" value="{{ old('capacity', 1) }}"
                               class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                    </div>
                </div>
                <div>
                    <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Description *</label>
                    <textarea name="description" rows="4" required data-wizard-required
                              class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none"
                              placeholder="Describe your workspace...">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Contact Phone *</label>
                        <input type="tel" name="contact_phone" required value="{{ old('contact_phone', auth()->user()->phone) }}" data-wizard-required
                               class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                        @error('contact_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">WhatsApp Number *</label>
                        <input type="tel" name="whatsapp_number" required value="{{ old('whatsapp_number', auth()->user()->phone) }}" data-wizard-required
                               class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                        @error('whatsapp_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">Website (optional)</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                </div>
            </div>
        </div>

        {{-- Step 2: Amenities --}}
        <div class="wizard-step" data-step="2">
            <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-6">Amenities</h2>
            <p class="font-inter text-sm text-on-surface-variant mb-5">Select all amenities available at your workspace</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($amenities as $amenity)
                    <label class="amenity-card cursor-pointer">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="sr-only"
                               {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}>
                        <div class="amenity-inner border border-outline-variant/60 rounded-xl p-4 text-center hover:border-primary-container/40 transition-colors relative">
                            <span class="amenity-check absolute top-2 right-2 w-5 h-5 rounded-full bg-primary-container text-white text-xs flex items-center justify-center opacity-0 transition-opacity">✓</span>
                            <span class="material-symbols-outlined text-2xl text-on-surface-variant mb-2 block">
                                {{ $amenity->icon ? 'check_circle' : 'star' }}
                            </span>
                            <span class="font-inter text-xs font-medium text-on-surface">{{ $amenity->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Step 3: Photos --}}
        <div class="wizard-step" data-step="3">
            <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-2">Upload Photos</h2>
            <p class="font-inter text-sm text-on-surface-variant mb-6">Upload at least one photo. The first image should be an exterior shot of the building.</p>
            <div class="border-2 border-dashed border-outline-variant rounded-2xl p-8 md:p-12 text-center hover:border-primary-container/50 transition-colors"
                 id="drop-zone">
                <span class="material-symbols-outlined text-5xl text-outline mb-4">cloud_upload</span>
                <p class="font-inter text-sm text-on-surface mb-1">Drag and drop photos here, or click to browse</p>
                <p class="font-inter text-xs text-on-surface-variant mb-4">JPG, PNG, GIF up to 2MB each</p>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/gif"
                       class="hidden" id="image-upload">
                <label for="image-upload"
                       class="inline-flex items-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors cursor-pointer">
                    Choose Files
                </label>
                <p id="image-count" class="font-inter text-sm text-on-surface-variant mt-4"></p>
                <p id="image-error" class="mt-2 text-sm text-red-600 hidden"></p>
                @error('images')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div id="image-preview" class="grid grid-cols-3 sm:grid-cols-5 gap-3 mt-6"></div>
        </div>

        {{-- Step 4: Pricing --}}
        <div class="wizard-step" data-step="4">
            <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-6">Pricing & Discounts</h2>
            <div class="space-y-5 max-w-lg">
                <div>
                    <label class="block font-inter text-sm font-medium text-on-surface mb-1.5">List Price (per day) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-inter text-sm text-on-surface-variant">₦</span>
                        <input type="number" name="price" required min="0" step="0.01" value="{{ old('price') }}" data-wizard-required
                               class="w-full rounded-lg border border-outline-variant pl-8 pr-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none">
                    </div>
                    @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="rounded-xl bg-surface-container-low/80 border border-outline-variant/40 p-4">
                    <p class="font-inter text-sm text-on-surface-variant">
                        All listings are priced per day. Your listing will be submitted for admin approval before going live.
                    </p>
                </div>
            </div>
        </div>

        {{-- Step 5: Review --}}
        <div class="wizard-step" data-step="5">
            <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-6">Review & Publish</h2>
            <div class="space-y-4 rounded-xl border border-outline-variant/60 divide-y divide-outline-variant/40" id="review-summary">
                <div class="p-4">
                    <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-1">Workspace</p>
                    <p class="font-manrope font-semibold text-[#1c2c40]" id="review-name">—</p>
                </div>
                <div class="p-4">
                    <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-1">Location</p>
                    <p class="font-inter text-sm text-on-surface" id="review-address">—</p>
                </div>
                <div class="p-4">
                    <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-1">Price</p>
                    <p class="font-manrope font-bold text-primary-container" id="review-price">—</p>
                </div>
                <div class="p-4">
                    <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-1">Amenities</p>
                    <p class="font-inter text-sm text-on-surface" id="review-amenities">—</p>
                </div>
                <div class="p-4">
                    <p class="font-inter text-xs text-on-surface-variant uppercase tracking-wide mb-1">Photos</p>
                    <p class="font-inter text-sm text-on-surface" id="review-photos">—</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-outline-variant/40">
            <button type="button" id="wizard-back"
                    class="font-inter text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors invisible">
                ← Back
            </button>
            <button type="button" id="wizard-next"
                    class="inline-flex items-center gap-2 bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
                Continue
            </button>
            <button type="submit" id="wizard-submit" class="hidden inline-flex items-center gap-2 bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
                Publish Listing
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    let currentStep = 1;
    const totalSteps = 5;
    const form = document.getElementById('listing-wizard-form');
    const steps = document.querySelectorAll('.wizard-step');
    const backBtn = document.getElementById('wizard-back');
    const nextBtn = document.getElementById('wizard-next');
    const submitBtn = document.getElementById('wizard-submit');
    const indicators = document.querySelectorAll('[data-step-indicator]');

    function updateUI() {
        steps.forEach(s => s.classList.toggle('active', parseInt(s.dataset.step) === currentStep));
        backBtn.classList.toggle('invisible', currentStep === 1);
        nextBtn.classList.toggle('hidden', currentStep === totalSteps);
        submitBtn.classList.toggle('hidden', currentStep !== totalSteps);
        indicators.forEach(el => {
            const n = parseInt(el.dataset.stepIndicator);
            el.classList.remove('done', 'active');
            if (n < currentStep) el.classList.add('done');
            if (n === currentStep) el.classList.add('active');
        });
        if (currentStep === totalSteps) populateReview();
    }

    function validateStep(step) {
        const panel = document.querySelector(`.wizard-step[data-step="${step}"]`);
        const required = panel.querySelectorAll('[data-wizard-required]');
        for (const field of required) {
            if (!field.value.trim()) {
                field.focus();
                field.classList.add('ring-2', 'ring-red-300');
                return false;
            }
            field.classList.remove('ring-2', 'ring-red-300');
        }
        return true;
    }

    function populateReview() {
        const name = form.querySelector('[name="name"]').value;
        const address = form.querySelector('[name="address"]').value;
        const citySelect = form.querySelector('[name="city_id"]');
        const cityText = citySelect.options[citySelect.selectedIndex]?.text || '';
        const price = form.querySelector('[name="price"]').value;
        const amenities = [...form.querySelectorAll('[name="amenities[]"]:checked')].map(cb => cb.closest('label').innerText.trim());
        const files = document.getElementById('image-upload').files;

        document.getElementById('review-name').textContent = name || '—';
        document.getElementById('review-address').textContent = [address, cityText !== 'Select city' ? cityText : ''].filter(Boolean).join(', ') || '—';
        document.getElementById('review-price').textContent = price ? '₦' + Number(price).toLocaleString() + '/day' : '—';
        document.getElementById('review-amenities').textContent = amenities.length ? amenities.join(', ') : 'None selected';
        document.getElementById('review-photos').textContent = files.length ? files.length + ' photo(s) selected' : 'No photos';
    }

    backBtn.addEventListener('click', () => {
        if (currentStep > 1) { currentStep--; updateUI(); }
    });

    nextBtn.addEventListener('click', () => {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) { currentStep++; updateUI(); }
    });

    const imageInput = document.getElementById('image-upload');
    const imageCount = document.getElementById('image-count');
    const imageError = document.getElementById('image-error');
    const imagePreview = document.getElementById('image-preview');
    const maxSize = 2 * 1024 * 1024;

    imageInput.addEventListener('change', function () {
        const files = this.files;
        let valid = 0;
        let oversized = [];
        imagePreview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxSize) {
                oversized.push(files[i].name);
            } else {
                valid++;
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-20 object-cover rounded-lg';
                    imagePreview.appendChild(img);
                };
                reader.readAsDataURL(files[i]);
            }
        }

        if (oversized.length) {
            imageError.textContent = 'Some files exceed 2MB: ' + oversized.join(', ');
            imageError.classList.remove('hidden');
        } else {
            imageError.classList.add('hidden');
        }
        imageCount.textContent = valid ? valid + ' file(s) selected' : '';
    });

    updateUI();
})();
</script>
@endpush
@endsection
