@extends('layouts.admin')

@section('title', 'Edit Post | GridSpace')

@section('admin_content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blog.index') }}"
           class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/60 hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="font-manrope text-2xl md:text-3xl font-bold text-[#1c2c40] tracking-tight">Edit Post</h1>
            <p class="font-inter text-sm text-on-surface-variant truncate max-w-md">{{ $post->title }}</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        @if($post->status === 'published')
            <a href="{{ route('blog.show', $post) }}" target="_blank"
               class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/60 hover:bg-surface-container" title="Preview">
                <span class="material-symbols-outlined text-[20px]">open_in_new</span>
            </a>
        @endif
        <button type="submit" form="blog-post-form"
                onclick="document.getElementById('status').value='draft'"
                class="px-4 py-2 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
            Save as Draft
        </button>
        <button type="submit" form="blog-post-form"
                onclick="document.getElementById('status').value='published'"
                class="px-5 py-2 rounded-lg bg-primary-container text-white font-inter text-sm font-semibold hover:bg-primary transition-colors">
            {{ $post->status === 'published' ? 'Update' : 'Publish' }}
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">{{ session('success') }}</div>
@endif

@include('admin.blog._form')

@if($post->exists)
    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="mt-6 max-w-xs"
          onsubmit="return confirm('Delete this post permanently?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-full py-2.5 rounded-lg border border-red-300 text-red-600 font-inter text-sm font-semibold hover:bg-red-50 transition-colors">
            Delete Post
        </button>
    </form>
@endif
@endsection
