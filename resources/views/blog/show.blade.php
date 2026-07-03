@extends('layouts.gridspace')

@section('title', $post->title . ' | GridSpace Blog')

@section('content')
<article class="bg-surface -mx-4 md:-mx-margin-desktop">
    @if($post->image_url)
        <div class="h-64 md:h-96 overflow-hidden">
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="max-w-3xl mx-auto px-4 md:px-margin-desktop py-10 md:py-16">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 font-inter text-sm text-primary-container hover:underline mb-6">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Blog
        </a>

        <div class="flex flex-wrap items-center gap-3 mb-6">
            <span class="px-3 py-1 bg-primary-container/10 text-primary-container rounded-full font-mono text-xs uppercase tracking-wide">{{ $post->category }}</span>
            <span class="font-mono text-xs text-secondary uppercase">{{ $post->date }}</span>
            <span class="font-mono text-xs text-secondary uppercase">{{ $post->read_time }} min read</span>
        </div>

        <h1 class="font-manrope text-3xl md:text-5xl font-extrabold text-on-surface mb-6 tracking-tight leading-tight">{{ $post->title }}</h1>

        @if($post->excerpt)
            <p class="font-inter text-xl text-secondary mb-8 leading-relaxed">{{ $post->excerpt }}</p>
        @endif

        <div class="flex items-center gap-4 mb-10 pb-10 border-b border-outline-variant">
            <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center font-bold text-on-secondary-container">
                {{ $post->author_initials }}
            </div>
            <div>
                <p class="font-inter font-bold text-on-surface">{{ $post->author_display }}</p>
                @if($post->author_role)
                    <p class="font-mono text-xs text-secondary uppercase">{{ $post->author_role }}</p>
                @endif
            </div>
        </div>

        <div class="prose prose-lg max-w-none font-inter text-on-surface leading-relaxed whitespace-pre-wrap">{{ $post->content }}</div>

        @if($post->tags && count($post->tags))
            <div class="mt-10 pt-8 border-t border-outline-variant flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                    <span class="px-3 py-1 bg-surface-container rounded-full font-inter text-xs text-on-surface-variant">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </div>
</article>

@if($related->isNotEmpty())
<section class="py-12 md:py-16 bg-surface-container-low -mx-4 md:-mx-margin-desktop px-4 md:px-margin-desktop">
    <div class="max-w-container-max mx-auto">
        <h2 class="font-manrope text-2xl font-bold text-on-surface mb-8">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($related as $article)
                @include('blog.partials.card', ['article' => $article])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
