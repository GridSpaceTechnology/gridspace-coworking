{{-- Add Listing modal wizard (included on host dashboard) --}}
<div id="listing-modal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true" aria-labelledby="listing-modal-title">
    <div class="absolute inset-0 bg-black/50" onclick="closeListingModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[92vh] flex flex-col pointer-events-auto shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/40 shrink-0">
                <div>
                    <h2 id="listing-modal-title" class="font-manrope text-xl font-bold text-[#1c2c40]">Add New Listing</h2>
                    <p class="font-inter text-xs text-on-surface-variant mt-0.5">Complete each step to publish your workspace</p>
                </div>
                <button type="button" onclick="closeListingModal()"
                        class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors"
                        aria-label="Close">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="px-6 pt-4 shrink-0 overflow-x-auto">
                <div class="flex items-center gap-2 min-w-max pb-2" id="wizard-progress">
                    @foreach(['Basic Info', 'Amenities', 'Photos', 'Pricing', 'Review'] as $i => $label)
                        <div class="flex items-center gap-2 shrink-0">
                            <div class="step-pill w-7 h-7 rounded-full flex items-center justify-center font-inter text-xs font-bold bg-surface-container text-on-surface-variant transition-colors"
                                 data-step-indicator="{{ $i + 1 }}">{{ $i + 1 }}</div>
                            <span class="font-inter text-xs font-medium text-on-surface-variant hidden sm:inline">{{ $label }}</span>
                            @if($i < 4)<div class="w-4 h-0.5 bg-outline-variant/50 hidden sm:block"></div>@endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-4">
                <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" id="listing-wizard-form">
                    @csrf
                    <input type="hidden" name="price_period" value="night">

                    <div class="wizard-step active" data-step="1">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">Basic Information</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">Workspace Name *</label>
                                    <input type="text" name="name" value="{{ old('name') }}" data-wizard-required
                                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">Workspace Type *</label>
                                    <select name="category_id" data-wizard-required class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                        <option value="">Select type</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="block font-inter text-sm font-medium mb-1">Full Address *</label>
                                <input type="text" name="address" value="{{ old('address') }}" data-wizard-required
                                       class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">Location</label>
                                    <select name="city_id" class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                        <option value="">Select city</option>
                                        @foreach($cities->groupBy('state') as $state => $stateCities)
                                            @if($state)<optgroup label="{{ $state }}">@endif
                                            @foreach($stateCities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                            @endforeach
                                            @if($state)</optgroup>@endif
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">Capacity</label>
                                    <input type="number" name="capacity" min="1" value="{{ old('capacity', 1) }}"
                                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                </div>
                            </div>
                            <div>
                                <label class="block font-inter text-sm font-medium mb-1">Description *</label>
                                <textarea name="description" rows="3" data-wizard-required
                                          class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">{{ old('description') }}</textarea>
                                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">Contact Phone *</label>
                                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}" data-wizard-required
                                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                    @error('contact_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block font-inter text-sm font-medium mb-1">WhatsApp *</label>
                                    <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()->phone) }}" data-wizard-required
                                           class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                                    @error('whatsapp_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wizard-step" data-step="2">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">Amenities</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($amenities as $amenity)
                                <label class="amenity-card cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="sr-only"
                                           {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}>
                                    <div class="amenity-inner border border-outline-variant/60 rounded-xl p-3 text-center hover:border-primary-container/40 relative">
                                        <span class="amenity-check absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-primary-container text-white text-[10px] flex items-center justify-center opacity-0">✓</span>
                                        <span class="material-symbols-outlined text-xl text-on-surface-variant mb-1 block">star</span>
                                        <span class="font-inter text-xs font-medium">{{ $amenity->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="wizard-step" data-step="3">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">Upload Photos</h3>
                        <p class="font-inter text-xs text-on-surface-variant mb-4">First image should be an exterior shot of the building.</p>
                        <div class="border-2 border-dashed border-outline-variant rounded-xl p-8 text-center">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">cloud_upload</span>
                            <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/gif" class="hidden" id="image-upload">
                            <label for="image-upload" class="inline-flex items-center gap-2 bg-primary-container text-white px-4 py-2 rounded-lg font-inter text-sm font-semibold cursor-pointer hover:bg-primary">Choose Files</label>
                            <p id="image-count" class="font-inter text-xs text-on-surface-variant mt-3"></p>
                            <p id="image-error" class="mt-2 text-sm text-red-600 hidden"></p>
                            @error('images')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div id="image-preview" class="grid grid-cols-4 gap-2 mt-4"></div>
                    </div>

                    <div class="wizard-step" data-step="4">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">Pricing</h3>
                        <div class="max-w-sm">
                            <label class="block font-inter text-sm font-medium mb-1">List Price (per day) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-on-surface-variant">₦</span>
                                <input type="number" name="price" min="0" step="0.01" value="{{ old('price') }}" data-wizard-required
                                       class="w-full rounded-lg border border-outline-variant pl-8 pr-3 py-2.5 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                            </div>
                            @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="wizard-step" data-step="5">
                        <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-4">Review & Publish</h3>
                        <div class="rounded-xl border border-outline-variant/60 divide-y divide-outline-variant/40 text-sm">
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Workspace</span><p class="font-semibold mt-0.5" id="review-name">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Location</span><p class="mt-0.5" id="review-address">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Price</span><p class="font-bold text-primary-container mt-0.5" id="review-price">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Amenities</span><p class="mt-0.5" id="review-amenities">—</p></div>
                            <div class="p-3"><span class="text-xs text-on-surface-variant uppercase">Photos</span><p class="mt-0.5" id="review-photos">—</p></div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between px-6 py-4 border-t border-outline-variant/40 shrink-0 bg-white">
                <button type="button" id="wizard-back" class="font-inter text-sm font-semibold text-on-surface-variant hover:text-on-surface invisible">← Back</button>
                <button type="button" id="wizard-next" class="bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter text-sm font-semibold hover:bg-primary">Continue</button>
                <button type="submit" form="listing-wizard-form" id="wizard-submit" class="hidden bg-primary-container text-white px-6 py-2.5 rounded-lg font-inter text-sm font-semibold hover:bg-primary">Publish Listing</button>
            </div>
        </div>
    </div>
</div>
