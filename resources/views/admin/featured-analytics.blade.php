@extends('layouts.app')

@section('title', 'Featured Analytics - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Featured Listings Analytics</h1>
        <p class="text-gray-600 mt-2">Track performance and ROI of featured listings</p>
    </div>

    <!-- Revenue Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                    <i class="fas fa-money-bill-wave text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">₦{{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Featured</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeFeatured }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg. ROI</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $avgROI }}x</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Inquiries</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalInquiries }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Marketing Insights -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
            Marketing Insights
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-900 mb-3">Top Performing Areas</h3>
                <div class="space-y-2">
                    @foreach($topAreas as $area)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-blue-700">{{ $area['city'] }}</span>
                            <span class="text-sm font-medium text-blue-900">{{ $area['count'] }} featured</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-900 mb-3">Conversion Opportunities</h3>
                <div class="space-y-3">
                    <div class="flex items-center text-sm text-green-700">
                        <i class="fas fa-arrow-up mr-2"></i>
                        <span>Hosts with 10+ inquiries are 3x more likely to upgrade to premium</span>
                    </div>
                    <div class="flex items-center text-sm text-green-700">
                        <i class="fas fa-arrow-up mr-2"></i>
                        <span>Featured listings get 10x more views than regular listings</span>
                    </div>
                    <div class="flex items-center text-sm text-green-700">
                        <i class="fas fa-arrow-up mr-2"></i>
                        <span>Premium plan has 80% renewal rate</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Listings Performance -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Featured Listings Performance</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inquiries</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ROI</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($featuredPerformance as $listing)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $listing->name }}</div>
                                <div class="text-sm text-gray-500">{{ $listing->address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($listing->featured_plan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($listing->views) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $listing->inquiries }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $listing->bookings }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ₦{{ number_format($listing->revenue) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full 
                                    {{ $listing->roi >= 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $listing->roi }}x
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Button -->
    <div class="mt-6">
        <a href="{{ route('admin.featured-analytics.export') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700">
            <i class="fas fa-download mr-2"></i>
            Export Analytics
        </a>
    </div>
</div>
@endsection
