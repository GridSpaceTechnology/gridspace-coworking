@extends('layouts.app')

@section('title', 'Request Featured Listing - Gridspace')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-star text-yellow-500 mr-2"></i>
                Request Featured Listing
            </h2>
        </div>

        <div class="p-6">
            <!-- Listing Preview -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Listing Details</h3>
                <div class="flex items-center space-x-4">
                    @if($listing->images->count() > 0)
                        <img src="{{ asset('storage/' . $listing->images->first()->image_path) }}"
                             alt="{{ $listing->name }}"
                             class="h-20 w-20 rounded-lg object-cover">
                    @else
                        <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-building text-gray-400 text-xl"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $listing->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $listing->category->name ?? 'No Category' }}</p>
                        <p class="text-sm text-gray-500">{{ $listing->location ?? 'No Location' }}</p>
                    </div>
                </div>
            </div>

            <!-- Feature Request Form -->
            <form action="{{ route('feature-requests.store', $listing->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Request Message -->
                    <div>
                        <label for="request_message" class="block text-sm font-medium text-gray-700 mb-2">
                            Why should this listing be featured? <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="request_message" name="request_message" rows="4"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                  placeholder="Tell us why your listing should be featured (optional)...">{{ old('request_message') }}</textarea>
                        @error('request_message')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Proof -->
                    <div>
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Proof <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-2">
                                Upload payment proof (receipt, screenshot, or confirmation)
                            </p>
                            <input type="file" id="payment_proof" name="payment_proof" required
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="block mx-auto text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 cursor-pointer">
                            @error('payment_proof')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                Accepted formats: JPG, PNG, PDF (Max 2MB)
                            </p>
                        </div>
                    </div>

                    <!-- Feature Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-medium text-blue-900 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Featured Listing Benefits
                        </h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Appear at the top of search results</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Get priority placement on homepage</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Increased visibility and bookings</li>
                            <li><i class="fas fa-check text-blue-600 mr-2"></i>Special "Featured" badge on your listing</li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('listings.show', $listing->slug) }}"
                           class="text-gray-600 hover:text-gray-800 text-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Listing
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                            <i class="fas fa-star mr-2"></i>
                            Submit Feature Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
