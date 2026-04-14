@extends('layouts.app')

@section('title', 'Edit Listing - Gridspace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Listing</h1>
        <p class="text-gray-600 mt-2">Update your workspace listing details.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('listings.update', $listing) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Listing Name *</label>
                        <input type="text"
                               name="name"
                               required
                               value="{{ old('name', $listing->name) }}"
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
                                <option value="{{ $category->id }}" {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
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
                              placeholder="Describe your workspace, its features, and what makes it special...">{{ old('description', $listing->description) }}</textarea>
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
                            @foreach($cities->groupBy('state') as $state => $stateCities)
                                @if($state)
                                    <optgroup label="{{ $state }}">
                                        @foreach($stateCities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $listing->city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    @foreach($stateCities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $listing->city_id) == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                @endif
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
                               value="{{ old('address', $listing->address) }}"
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
                               value="{{ old('contact_phone', $listing->contact_phone) }}"
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
                               value="{{ old('whatsapp_number', $listing->whatsapp_number) }}"
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
                           value="{{ old('website', $listing->website) }}"
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
                               value="{{ old('price', $listing->price) }}"
                               placeholder="e.g., 15000"
                               min="0"
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="price_period" value="per_day">
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
                                   {{ in_array($amenity->id, old('amenities', $listing->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
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

            <!-- Current Images -->
            @if($listing->images->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Images</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($listing->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="{{ $listing->name }}"
                                     class="w-full h-32 object-cover rounded-lg">
                                @if($image->is_external)
                                    <div class="absolute top-0 left-0 bg-green-500 text-white text-xs px-2 py-1 rounded-tl-lg rounded-br-lg">
                                        <i class="fas fa-building"></i> External
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-xs">Image {{ $loop->iteration }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Note: To remove images, you'll need to delete and re-upload them.</p>
                </div>
            @endif

            <!-- Add New Images -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Add New Images</h2>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 mb-2">Upload additional images of your workspace</p>
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
                    Update Listing
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
