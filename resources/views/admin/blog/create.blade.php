@extends('layouts.admin')

@section('title', 'Create New Post | GridSpace')

@section('admin_content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blog.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/60 hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="font-manrope text-2xl md:text-3xl font-bold text-[#1c2c40] tracking-tight">Create New Post</h1>
            <p class="font-inter text-sm text-on-surface-variant">Write and publish content for the GridSpace blog</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button type="submit" form="blog-post-form" name="status" value="draft" formaction="{{ route('admin.blog.store') }}"
                onclick="document.getElementById('status').value='draft'"
                class="px-4 py-2 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
            Save as Draft
        </button>
        <button type="submit" form="blog-post-form"
                onclick="document.getElementById('status').value='published'"
                class="px-5 py-2 rounded-lg bg-primary-container text-white font-inter text-sm font-semibold hover:bg-primary transition-colors">
            Publish
        </button>
    </div>
</div>

@include('admin.blog._form')
@endsection
