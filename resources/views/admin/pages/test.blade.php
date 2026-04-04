@extends('layouts.app')

@section('title', 'Pages - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900">Pages Management</h1>
    <p class="mt-2 text-gray-600">Manage static pages and content</p>
    
    <div class="bg-white rounded-lg shadow p-6 mt-8">
        <h2 class="text-lg font-medium text-gray-900">All Pages</h2>
        <p class="text-gray-500">Total Pages: {{ $pages->count() }}</p>
    </div>
</div>
@endsection
