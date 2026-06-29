@extends('layouts.gridspace')

@section('title', 'GridSpace | Insights into the Future of Work')

@push('head')
<style>
    .article-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .article-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(10, 37, 64, 0.05); }
    .active-filter { background-color: #ff5a1f; color: white; border-color: #ff5a1f; }
</style>
@endpush

@section('content')
{{-- Hero --}}
<section class="relative -mx-4 md:-mx-margin-desktop mb-0 min-h-[400px] md:min-h-[500px] lg:min-h-[614px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img
            class="w-full h-full object-cover"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAL08WdxRj6O3-z65FgoQbrhYCmXAonz4vZ-6TWm5grMlrwO4IimHG6Jzz5wsF1maYIhqxBJII6zk6POHfne1vOgZp-ECDMn8xqT-hn3gppVjNd8lLZ20S1jeJa-VlbwZKXidaz9NPUC0qoQ5Wloywdr_X4qlHQkmNOXqzwPc4P_-tGm7EyhM6_1V7rqnRQlMHrEdv419KPGLpy0ZUKtawImgpr1TEdyJprxOsNVKDmuY-0G-BokToF-jz0N4Sq7Mv1qXewV7FOkhY"
            alt="Lagos cityscape at dusk"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-on-surface/90 to-on-surface/40"></div>
    </div>
    <div class="relative z-10 w-full max-w-container-max mx-auto px-4 md:px-margin-desktop py-16">
        <div class="max-w-2xl">
            <span class="font-mono text-xs uppercase tracking-widest text-primary-container mb-4 block">Our Journal</span>
            <h1 class="font-manrope text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">Insights into the Future of Work</h1>
            <p class="font-inter text-lg text-surface-variant mb-8 max-w-lg">Discover professional advice, workspace trends, and success stories from Africa's leading network of on-demand workspaces.</p>
            <form method="GET" action="{{ route('blog.index') }}" class="flex gap-4 max-w-md">
                @if(request('category') && request('category') !== 'all')
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-outline">search</span>
                    <input
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-4 rounded-xl border-none focus:ring-2 focus:ring-primary-container bg-white shadow-xl text-on-surface font-inter outline-none"
                        placeholder="Search articles..."
                        type="search"
                    >
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Featured --}}
@if($featured)
<section class="py-12 md:py-16 bg-surface -mx-4 md:-mx-margin-desktop px-4 md:px-margin-desktop">
    <div class="max-w-container-max mx-auto">
        <h2 class="font-manrope text-2xl md:text-3xl font-bold text-on-surface mb-8">Featured Story</h2>
        <article class="article-card group relative bg-white border border-outline-variant rounded-2xl overflow-hidden flex flex-col md:flex-row cursor-pointer">
            <div class="md:w-1/2 h-64 md:h-auto min-h-[280px] overflow-hidden">
                <img src="{{ $featured['image'] }}" alt="{{ $featured['title'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
            </div>
            <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-6 flex-wrap">
                    <span class="px-3 py-1 bg-primary-container/10 text-primary-container rounded-full font-mono text-xs uppercase tracking-wide">{{ $featured['category'] }}</span>
                    <span class="text-secondary font-mono text-xs uppercase tracking-wide">{{ $featured['read_time'] }} MIN READ</span>
                </div>
                <h3 class="font-manrope text-2xl md:text-4xl font-bold text-on-surface mb-6 group-hover:text-primary transition-colors leading-tight">{{ $featured['title'] }}</h3>
                <p class="font-inter text-lg text-secondary mb-8">{{ $featured['excerpt'] }}</p>
                @if(!empty($featured['author_name']))
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold">{{ $featured['author_initials'] }}</div>
                        <div>
                            <p class="font-inter font-bold text-on-surface">{{ $featured['author_name'] }}</p>
                            <p class="font-mono text-xs text-secondary uppercase tracking-wide">{{ $featured['author_role'] }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </article>
    </div>
</section>
@endif

{{-- Filters & grid --}}
<section class="py-12 md:py-16 bg-surface-container-low -mx-4 md:-mx-margin-desktop px-4 md:px-margin-desktop">
    <div class="max-w-container-max mx-auto">
        <div class="flex flex-wrap items-center justify-between gap-6 mb-12 border-b border-outline-variant pb-6">
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $category)
                    <a
                        href="{{ route('blog.index', array_filter(['category' => $category['slug'] !== 'all' ? $category['slug'] : null, 'search' => request('search')])) }}"
                        class="px-6 py-2 rounded-full border border-outline-variant font-inter text-sm font-medium transition-all hover:border-primary-container
                            {{ $activeCategory === $category['slug'] ? 'active-filter' : 'bg-white hover:text-primary-container' }}"
                    >{{ $category['label'] }}</a>
                @endforeach
            </div>
            <div class="flex items-center gap-2 text-secondary font-inter text-sm">
                <span class="material-symbols-outlined">filter_list</span>
                <span>Sort by: Latest</span>
            </div>
        </div>

        @if($posts->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-outline-variant">
                <span class="material-symbols-outlined text-5xl text-outline-variant mb-4">article</span>
                <h3 class="font-manrope text-xl font-bold text-on-surface mb-2">No articles found</h3>
                <p class="font-inter text-secondary mb-6">Try a different search or category.</p>
                <a href="{{ route('blog.index') }}" class="text-primary-container font-semibold hover:underline">View all articles</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $article)
                    @include('blog.partials.card', ['article' => $article])
                @endforeach
            </div>
        @endif

        @if($posts->count() > 0)
            <div class="mt-16 flex items-center justify-center gap-4">
                <button type="button" class="w-12 h-12 rounded-full border border-outline-variant flex items-center justify-center opacity-50 cursor-not-allowed" disabled>
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="flex items-center gap-2">
                    <span class="w-10 h-10 rounded-lg bg-primary-container text-white font-bold flex items-center justify-center">1</span>
                </div>
                <button type="button" class="w-12 h-12 rounded-full border border-outline-variant flex items-center justify-center opacity-50 cursor-not-allowed" disabled>
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        @endif
    </div>
</section>

{{-- Newsletter --}}
<section class="py-12 md:py-16 bg-[#1c2c40] relative overflow-hidden -mx-4 md:-mx-margin-desktop">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-0 left-0 w-64 h-64 bg-primary-container rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary-fixed rounded-full blur-[120px] translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 md:px-margin-desktop text-center">
        <h2 class="font-manrope text-2xl md:text-3xl font-bold text-white mb-6">Stay ahead of the future of work.</h2>
        <p class="font-inter text-lg text-tertiary-fixed-dim mb-10">Subscribe to our newsletter and get the latest workspace insights, host tips, and member stories delivered to your inbox every week.</p>
        <form class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto" onsubmit="event.preventDefault();">
            <input class="flex-grow px-6 py-4 rounded-xl border-none bg-white/10 text-white placeholder:text-white/40 focus:ring-2 focus:ring-primary-container backdrop-blur-sm outline-none font-inter" placeholder="Your work email address" type="email">
            <button type="submit" class="bg-primary-container text-white px-8 py-4 rounded-xl font-manrope font-bold hover:opacity-90 transition-all shadow-lg shadow-primary-container/20 shrink-0">Subscribe Now</button>
        </form>
        <p class="font-mono text-xs text-white/50 mt-6 uppercase tracking-wide">No spam, just quality insights. Unsubscribe at any time.</p>
    </div>
</section>
@endsection
