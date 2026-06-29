<article class="article-card group bg-white rounded-2xl overflow-hidden border border-outline-variant flex flex-col h-full">
    <div class="relative h-56 overflow-hidden">
        <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute top-4 left-4">
            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-primary font-mono text-xs uppercase tracking-wide rounded-lg shadow-sm">{{ $article['category'] }}</span>
        </div>
    </div>
    <div class="p-6 flex flex-col flex-grow">
        <div class="flex items-center justify-between mb-4 gap-2">
            <span class="text-secondary font-mono text-xs uppercase tracking-wide">{{ $article['date'] }}</span>
            <span class="text-secondary font-mono text-xs uppercase tracking-wide flex items-center gap-1 shrink-0">
                <span class="material-symbols-outlined text-base">schedule</span>
                {{ $article['read_time'] }} MIN
            </span>
        </div>
        <h4 class="font-manrope text-xl font-semibold text-on-surface mb-3 leading-tight group-hover:text-primary transition-colors">{{ $article['title'] }}</h4>
        <p class="font-inter text-secondary mb-6 line-clamp-3 flex-grow">{{ $article['excerpt'] }}</p>
        <div class="mt-auto pt-6 border-t border-outline-variant flex items-center justify-between">
            <span class="font-inter font-bold text-primary-container group-hover:underline">Read Article</span>
            <span class="material-symbols-outlined text-primary-container">arrow_forward</span>
        </div>
    </div>
</article>
