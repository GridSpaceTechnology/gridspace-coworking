{{-- Add Listing modal: building + multiple bookable spaces --}}
<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    .step-pill.done { background-color: #ff5a1f; color: #fff; }
    .step-pill.active { background-color: #1c2c40; color: #fff; }
    .amenity-card input:checked + .amenity-inner {
        border-color: #ff5a1f;
        background-color: #fff5f0;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    .amenity-card input:checked + .amenity-inner .amenity-check { display: flex; }
    .amenity-card input:checked + .amenity-inner .amenity-icon { color: #ff5a1f; }
    .image-dropzone-active {
        border-color: #ff5a1f !important;
        background-color: #fff5f0;
    }
</style>
<div id="listing-modal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true" aria-labelledby="listing-modal-title">
    <div class="absolute inset-0 bg-black/50" onclick="closeListingModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[92vh] flex flex-col pointer-events-auto shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/40 shrink-0">
                <div>
                    <h2 id="listing-modal-title" class="font-manrope text-xl font-bold text-[#1c2c40]">Add Building & Spaces</h2>
                    <p class="font-inter text-xs text-on-surface-variant mt-0.5">List your building, then add each bookable space</p>
                </div>
                <button type="button" onclick="closeListingModal()"
                        class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors"
                        aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="px-6 pt-4 shrink-0 overflow-x-auto">
                <div class="flex items-center gap-2 min-w-max pb-2" id="wizard-progress">
                    @foreach(['Building', 'Photos', 'Spaces', 'Review'] as $i => $label)
                        <div class="flex items-center gap-2 shrink-0">
                            <div class="step-pill w-7 h-7 rounded-full flex items-center justify-center font-inter text-xs font-bold bg-surface-container text-on-surface-variant transition-colors"
                                 data-step-indicator="{{ $i + 1 }}">{{ $i + 1 }}</div>
                            <span class="font-inter text-xs font-medium text-on-surface-variant hidden sm:inline">{{ $label }}</span>
                            @if($i < 3)<div class="w-4 h-0.5 bg-outline-variant/50 hidden sm:block"></div>@endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4">
                <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" id="listing-wizard-form">
                    @csrf

                    {{-- Step 1: Building --}}
                    <div class="wizard-step active" data-step="1">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-1">Building details</h3>
                        <p class="font-inter text-xs text-on-surface-variant mb-4">This is the property clients will find. Spaces inside are booked separately.</p>
                        <div class="space-y-4">
                            <div>
                                <label class="block font-inter text-sm font-medium mb-1">Building / Property Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" data-wizard-required
                                       placeholder="e.g. Victoriaith Plaza Coworking"
                                       class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block font-inter text-sm font-medium mb-1">Full Address *</label>
                                <input type="text" name="address" value="{{ old('address') }}" data-wizard-required
                                       class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            @php
                                $wizardCities = $cities->filter(fn ($city) => filled($city->state))->sortBy(['state', 'name']);
                                $wizardStates = $wizardCities->pluck('state')->unique()->values();
                                $wizardCitiesByState = $wizardCities->groupBy('state')->map(
                                    fn ($group) => $group->map(fn ($city) => ['id' => $city->id, 'name' => $city->name])->values()
                                );
                                $wizardSelectedCityId = old('city_id');
                                $wizardSelectedCity = $wizardSelectedCityId
                                    ? $wizardCities->firstWhere('id', (int) $wizardSelectedCityId)
                                    : null;
                                $wizardSelectedState = $wizardSelectedCity?->state;
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">State *</label>
                                    <select id="wizard-state-select" data-wizard-required
                                            class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                        <option value="">Select state</option>
                                        @foreach($wizardStates as $state)
                                            <option value="{{ $state }}" @selected($wizardSelectedState === $state)>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">City *</label>
                                    <select name="city_id" id="wizard-city-select" data-wizard-required
                                            class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30 disabled:bg-surface-container"
                                            @disabled(! $wizardSelectedState)>
                                        <option value="">{{ $wizardSelectedState ? 'Select city' : 'Select a state first' }}</option>
                                    </select>
                                    @error('city_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="block font-inter text-sm font-medium mb-1">About the building *</label>
                                <textarea name="description" rows="3" data-wizard-required
                                          placeholder="Describe the building, location highlights, access, etc."
                                          class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">{{ old('description') }}</textarea>
                                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">Contact Phone *</label>
                                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}" data-wizard-required
                                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                </div>
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">WhatsApp *</label>
                                    <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()->phone) }}" data-wizard-required
                                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Photos --}}
                    <div class="wizard-step" data-step="2">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <div>
                                <h3 class="font-manrope text-lg font-bold text-[#1c2c40]">Building photos</h3>
                                <p class="font-inter text-xs text-on-surface-variant mt-0.5">Show the building exterior and common areas. First image is the cover.</p>
                            </div>
                            <p id="image-count" class="font-inter text-xs font-semibold text-primary-container shrink-0">0 / 10 photos</p>
                        </div>
                        <div id="image-dropzone"
                             class="border-2 border-dashed border-outline-variant rounded-xl p-8 text-center transition-colors hover:border-primary-container/50 cursor-pointer">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">add_photo_alternate</span>
                            <p class="font-inter text-sm font-medium text-[#1c2c40] mb-1">Drag & drop photos here</p>
                            <p class="font-inter text-xs text-on-surface-variant mb-4">JPG, PNG, GIF, WEBP · max 2MB each · up to 10</p>
                            <input type="file" name="images[]" id="image-upload" class="hidden" multiple accept="image/jpeg,image/png,image/gif,image/webp,image/jpg">
                            <button type="button" id="image-browse-btn"
                                    class="inline-flex items-center gap-2 bg-primary-container text-white px-4 py-2 rounded-lg font-inter text-sm font-semibold hover:bg-primary">
                                <span class="material-symbols-outlined text-[18px]">upload</span>
                                Choose Photos
                            </button>
                            <p id="image-error" class="mt-3 text-sm text-red-600 hidden"></p>
                        </div>
                        <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4"></div>
                    </div>

                    {{-- Step 3: Spaces --}}
                    <div class="wizard-step" data-step="3">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <div>
                                <h3 class="font-manrope text-lg font-bold text-[#1c2c40]">Bookable spaces</h3>
                                <p class="font-inter text-xs text-on-surface-variant mt-0.5">Add each space with its own type, amenities, and price. Clients book one space at a time.</p>
                            </div>
                            <button type="button" id="add-space-btn"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-outline-variant font-inter text-xs font-semibold text-[#1c2c40] hover:bg-surface-container shrink-0">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                                Add space
                            </button>
                        </div>
                        @error('spaces')<p class="mb-3 text-sm text-red-600">{{ $message }}</p>@enderror
                        <div id="spaces-container" class="space-y-4"></div>
                    </div>

                    {{-- Step 4: Review --}}
                    <div class="wizard-step" data-step="4">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">Review & submit</h3>
                        <div class="rounded-xl border border-outline-variant/60 divide-y divide-outline-variant/40 text-sm">
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Building</span><p class="font-semibold mt-0.5" id="review-name">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Location</span><p class="mt-0.5" id="review-address">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Photos</span><p class="mt-0.5" id="review-photos">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Spaces</span><div class="mt-1 space-y-1" id="review-spaces">—</div></div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between px-6 py-4 border-t border-outline-variant/40 shrink-0 bg-white">
                <button type="button" id="wizard-back" class="font-inter text-sm font-semibold text-on-surface-variant hover:text-on-surface invisible">← Back</button>
                <button type="button" id="wizard-next" class="bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter text-sm font-semibold hover:bg-primary">Continue</button>
                <button type="submit" form="listing-wizard-form" id="wizard-submit" class="hidden bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter text-sm font-semibold hover:bg-primary">Submit Listing</button>
            </div>
        </div>
    </div>
</div>

<template id="space-card-template">
    <div class="space-card rounded-xl border border-outline-variant/60 p-4 bg-surface-container-low/20" data-space-index="__INDEX__">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h4 class="font-manrope font-bold text-[#1c2c40] text-sm">Space <span class="space-number">__NUMBER__</span></h4>
            <button type="button" class="remove-space-btn text-red-600 hover:bg-red-50 rounded-lg px-2 py-1 font-inter text-xs font-semibold hidden">Remove</button>
        </div>
        <div class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-inter text-xs font-medium mb-1">Space name *</label>
                    <input type="text" name="spaces[__INDEX__][name]" data-space-required placeholder="e.g. Conference Room A"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                </div>
                <div>
                    <label class="block font-inter text-xs font-medium mb-1">Space type *</label>
                    <select name="spaces[__INDEX__][category_id]" data-space-required
                            class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                        <option value="">Select type</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block font-inter text-xs font-medium mb-1">Price (₦) *</label>
                    <input type="number" name="spaces[__INDEX__][price]" min="0" step="0.01" data-space-required
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                </div>
                <div>
                    <label class="block font-inter text-xs font-medium mb-1">Billed *</label>
                    <select name="spaces[__INDEX__][price_period]" data-space-required
                            class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                        <option value="hour">Per hour</option>
                        <option value="day" selected>Per day</option>
                        <option value="week">Per week</option>
                        <option value="month">Per month</option>
                    </select>
                </div>
                <div>
                    <label class="block font-inter text-xs font-medium mb-1">People capacity *</label>
                    <input type="number" name="spaces[__INDEX__][capacity]" min="1" value="1" data-space-required
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30"
                           placeholder="e.g. 8">
                    <p class="mt-1 font-inter text-[11px] text-on-surface-variant">Max people this space can hold</p>
                </div>
            </div>
            <div>
                <label class="block font-inter text-xs font-medium mb-1">Description</label>
                <textarea name="spaces[__INDEX__][description]" rows="2" placeholder="What makes this space unique?"
                          class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30"></textarea>
            </div>
            <div>
                <label class="block font-inter text-xs font-medium mb-1">Space photos <span class="font-normal text-on-surface-variant">(optional)</span></label>
                <p class="font-inter text-[11px] text-on-surface-variant mb-2">Upload multiple photos of this space. Max 10, 2MB each.</p>
                <input type="file"
                       name="spaces[__INDEX__][images][]"
                       class="space-images-input block w-full text-sm font-inter text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-primary-container file:text-white file:text-xs file:font-semibold hover:file:bg-primary"
                       multiple
                       accept="image/jpeg,image/png,image/gif,image/webp,image/jpg">
                <p class="space-images-count mt-1.5 font-inter text-[11px] text-on-surface-variant"></p>
            </div>
            <div>
                <label class="block font-inter text-xs font-medium mb-2">Amenities</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($amenities as $amenity)
                        <label class="amenity-card block cursor-pointer select-none">
                            <input type="checkbox" name="spaces[__INDEX__][amenities][]" value="{{ $amenity->id }}" class="sr-only">
                            <div class="amenity-inner relative border border-outline-variant/60 rounded-lg px-2 py-2 text-center transition-all hover:border-primary-container/40">
                                <span class="amenity-check absolute top-1 right-1 w-4 h-4 rounded-full bg-primary-container text-white text-[10px] items-center justify-center hidden">✓</span>
                                <span class="amenity-icon material-symbols-outlined text-lg text-on-surface-variant block">{{ $amenity->icon ?: 'star' }}</span>
                                <span class="font-inter text-[10px] font-medium text-[#1c2c40]">{{ $amenity->name }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</template>
