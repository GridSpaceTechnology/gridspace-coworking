@extends('layouts.app')

@section('title', 'Create Listing - Gridspace')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Listing</h1>
        <p class="text-gray-600 mt-2">Add your workspace to the Gridspace directory.</p>
    </div>

    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6 space-y-8">
        @csrf

        <!-- Basic Information -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Listing Name *</label>
                    <input type="text"
                           name="name"
                           required
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter your workspace name">
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

            <div>
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
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Location & Contact</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                    <select name="city_id"
                            required
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Full address">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone *</label>
                    <input type="tel"
                           name="contact_phone"
                           required
                           value="{{ old('contact_phone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="+2348000000000">
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="+2348000000000">
                    @error('whatsapp_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
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
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing & Capacity</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price Range *</label>
                    <input type="text"
                           name="price_range"
                           required
                           value="{{ old('price_range') }}"
                           placeholder="e.g., ₦500-1000/month or ₦25/hour"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('price_range')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capacity *</label>
                    <input type="number"
                           name="capacity"
                           value="{{ old('capacity') }}"
                           min="1"
                           placeholder="e.g., 10"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Amenities -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Amenities</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($amenities as $amenity)
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="amenities[]"
                               value="{{ $amenity->id }}"
                               {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                               class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">
                            @if($amenity->icon)
                                <i class="fas fa-{{ $amenity->icon }} mr-1"></i>
                            @endif
                            {{ $amenity->name }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('amenities')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Images & Video Upload -->
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Images & Video</h2>

            <!-- External Building Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-building mr-2 text-blue-600"></i>
                    External Building Image *
                    <span class="text-xs text-gray-500 ml-2">(Street view - what people see from outside)</span>
                </label>
                <div class="flex items-center space-x-4">
                    <input type="file"
                           name="external_image"
                           accept="image/jpeg,image/png,image/gif"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="previewExternalImage(this)">
                    @error('external_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div id="external-preview" class="hidden">
                        <img src="" alt="External building preview" class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                    </div>
                </div>
            </div>

            <!-- Internal Images (5 images) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-images mr-2 text-green-600"></i>
                    Internal Space Images *
                    <span class="text-xs text-gray-500 ml-2">(Interior views - up to 5 images, max 2MB each)</span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <input type="file"
                                   name="internal_images[]"
                                   accept="image/jpeg,image/png,image/gif"
                                   class="hidden"
                                   id="internal-image-{{ $i }}"
                                   onchange="previewInternalImage(this, {{ $i }})">

                            <div id="preview-{{ $i }}" class="flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-600">Image {{ $i }}</p>
                                    <p class="text-xs text-gray-500">Click to upload</p>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                @error('internal_images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Video Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-video mr-2 text-purple-600"></i>
                    Video Tour (Optional)
                    <span class="text-xs text-gray-500 ml-2">(MP4, MOV, AVI - max 50MB)</span>
                </label>

                <div class="flex items-center space-x-4">
                    <input type="file"
                           name="video"
                           accept="video/mp4,video/mov,video/avi"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="previewVideo(this)">

                    <div id="video-preview" class="hidden">
                        <video controls class="h-32 w-32 rounded-lg border-2 border-gray-300">
                            <source src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

                @error('video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('dashboard') }}"
               class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium hover:bg-gray-400">
                Cancel
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Create Listing
            </button>
        </div>
    </form>
</div>

<script>
function previewExternalImage(input) {
    const preview = document.getElementById('external-preview');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function previewInternalImage(input, index) {
    const preview = document.getElementById(`preview-${index}`);

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}"
                     alt="Internal space preview ${index}"
                     class="h-32 w-full object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200"
                     onclick="document.getElementById('internal-image-${index}').click()">
                <p class="text-xs text-gray-600 mt-1 text-center">Click to change</p>
            `;
        }
        reader.readAsDataURL(file);
    }
}

function previewVideo(input) {
    const preview = document.getElementById('video-preview');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Check file size (max 50MB)
        if (file.size > 50 * 1024 * 1024) {
            alert('Video size must be less than 50MB');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const video = preview.querySelector('video');
            video.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
