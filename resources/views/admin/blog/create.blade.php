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
        <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg border border-outline-variant/60 hover:bg-surface-container" title="Preview">
            <span class="material-symbols-outlined text-[20px]">visibility</span>
        </button>
        <button type="button" class="px-4 py-2 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container">
            Save as Draft
        </button>
        <button type="submit" form="blog-create-form"
                class="px-5 py-2 rounded-lg bg-primary-container text-white font-inter text-sm font-semibold hover:bg-primary transition-colors">
            Publish
        </button>
    </div>
</div>

<form id="blog-create-form" class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-gutter" onsubmit="event.preventDefault(); alert('Blog publishing will be available in a future update.');">
    {{-- Main content --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label class="block font-inter text-sm font-medium text-on-surface mb-2">Post Title</label>
            <input type="text" placeholder="Enter a compelling title..."
                   class="w-full text-2xl font-manrope font-bold text-[#1c2c40] border-0 border-b border-outline-variant/60 pb-3 focus:ring-0 focus:border-primary-container outline-none placeholder:text-outline">
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label class="block font-inter text-sm font-medium text-on-surface mb-3">Featured Image</label>
            <div class="border-2 border-dashed border-outline-variant rounded-xl p-10 text-center hover:border-primary-container/50 transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-4xl text-outline mb-2">upload</span>
                <p class="font-inter text-sm text-on-surface-variant">Click to upload featured image</p>
                <input type="file" accept="image/*" class="hidden">
            </div>
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label class="block font-inter text-sm font-medium text-on-surface mb-2">Excerpt</label>
            <textarea rows="3" placeholder="Short summary for listings and SEO..."
                      class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none resize-none"></textarea>
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label class="block font-inter text-sm font-medium text-on-surface mb-2">Content</label>
            <textarea rows="12" placeholder="Write your article content here..."
                      class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none resize-y"></textarea>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <h3 class="font-manrope font-bold text-[#1c2c40] mb-4">Post Settings</h3>
            <div class="space-y-4">
                <div>
                    <label class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Category</label>
                    <select class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                        <option value="">Select category</option>
                        @foreach($categories as $slug => $label)
                            <option value="{{ $slug }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Tags</label>
                    <input type="text" placeholder="workspace, tips, trends"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                </div>
            </div>
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <h3 class="font-manrope font-bold text-[#1c2c40] mb-4">Publishing</h3>
            <div class="space-y-4">
                <div>
                    <label class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Status</label>
                    <select class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <div class="flex justify-between font-inter text-sm">
                    <span class="text-on-surface-variant">Word count</span>
                    <span class="font-medium text-[#1c2c40]" id="word-count">0</span>
                </div>
                <div class="flex justify-between font-inter text-sm">
                    <span class="text-on-surface-variant">Est. read time</span>
                    <span class="font-medium text-[#1c2c40]">1 min</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <h3 class="font-manrope font-bold text-[#1c2c40] mb-3">SEO Preview</h3>
            <div class="rounded-lg border border-outline-variant/50 p-3 bg-surface-container-low/50">
                <p class="font-inter text-sm text-blue-700 font-medium truncate">Your Post Title — GridSpace Blog</p>
                <p class="font-inter text-xs text-green-700 truncate mt-0.5">cowork.gridspace.com.ng/blog/your-post</p>
                <p class="font-inter text-xs text-on-surface-variant mt-1 line-clamp-2">Your excerpt will appear here in search results...</p>
            </div>
        </div>
    </div>
</form>
@endsection
