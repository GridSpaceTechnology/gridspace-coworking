@extends('layouts.app')

@section('title', 'Create Listing - Gridspace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Listing</h1>
        <p class="text-gray-600 mt-2">Add your workspace to the Gridspace directory.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Listing Name *</label>
                        <input type="text"
                               name="name"
                               required
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category_id"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description"
                              rows="4"
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Describe your workspace, its features, and what makes it special...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Location & Contact -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Location & Contact</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <select name="city_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a city</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <input type="text"
                               name="address"
                               required
                               value="{{ old('address') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone *</label>
                        <input type="tel"
                               name="contact_phone"
                               required
                               value="{{ old('contact_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number *</label>
                        <input type="tel"
                               name="whatsapp_number"
                               required
                               value="{{ old('whatsapp_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                    <input type="url"
                           name="website"
                           value="{{ old('website') }}"
                           placeholder="https://example.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pricing & Capacity -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing & Capacity</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <input type="number"
                               name="price"
                               required
                               value="{{ old('price') }}"
                               placeholder="e.g., 15000"
                               min="0"
                               step="0.01"
                               class="w-full px-3 py-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Enter the base price in Naira (₦)</p>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Period *</label>
                        <select name="price_period"
                                class="w-full px-3 py-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="night" {{ old('price_period') == 'night' ? 'selected' : '' }}>Per Night</option>
                            <option value="week" {{ old('price_period') == 'week' ? 'selected' : '' }}>Per Week</option>
                            <option value="month" {{ old('price_period') == 'month' ? 'selected' : '' }}>Per Month</option>
                        </select>
                        @error('price_period')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Amenities</h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($amenities as $amenity)
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="amenities[]"
                                   value="{{ $amenity->id }}"
                                   {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                                   class="mr-2">
                            <span class="text-sm text-gray-700">
                                @if($amenity->icon)
                                    <i class="fas fa-{{ $amenity->icon }} mr-1"></i>
                                @endif
                                {{ $amenity->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Images -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Images</h2>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 mb-2">Upload images of your workspace</p>
                        <p class="text-sm text-gray-500 mb-4">JPG, PNG, GIF up to 2MB each</p>
                        <input type="file"
                               name="images[]"
                               multiple
                               accept="image/jpeg,image/png,image/gif"
                               class="hidden"
                               id="image-upload">
                        <label for="image-upload"
                               class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 cursor-pointer">
                            Choose Files
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700">
                    Create Listing
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('image-upload').addEventListener('change', function(e) {
    const files = e.target.files;
    const label = e.target.nextElementSibling;

    if (files.length > 0) {
        label.textContent = files.length + ' file(s) selected';
    } else {
        label.textContent = 'Choose Files';
    }
});
</script>
@endsection
