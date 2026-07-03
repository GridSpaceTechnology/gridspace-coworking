@php
    $isEdit = $post->exists;
    $formAction = $isEdit ? route('admin.blog.update', $post) : route('admin.blog.store');
    $tagsValue = old('tags', is_array($post->tags) ? implode(', ', $post->tags) : '');
@endphp

<form id="blog-post-form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-gutter">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label for="title" class="block font-inter text-sm font-medium text-on-surface mb-2">Post Title *</label>
            <input type="text" id="title" name="title" required value="{{ old('title', $post->title) }}"
                   placeholder="Enter a compelling title..."
                   class="w-full text-2xl font-manrope font-bold text-[#1c2c40] border-0 border-b border-outline-variant/60 pb-3 focus:ring-0 focus:border-primary-container outline-none placeholder:text-outline">
            @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label class="block font-inter text-sm font-medium text-on-surface mb-3">Featured Image</label>
            @if($post->image_url)
                <div class="mb-4 relative rounded-xl overflow-hidden">
                    <img src="{{ $post->image_url }}" alt="" class="w-full h-48 object-cover">
                    <label class="absolute top-3 right-3 flex items-center gap-2 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg text-xs font-inter cursor-pointer">
                        <input type="checkbox" name="remove_featured_image" value="1" class="rounded">
                        Remove image
                    </label>
                </div>
            @endif
            <div class="border-2 border-dashed border-outline-variant rounded-xl p-8 text-center hover:border-primary-container/50 transition-colors">
                <span class="material-symbols-outlined text-4xl text-outline mb-2">upload</span>
                <p class="font-inter text-sm text-on-surface-variant mb-3">Upload a featured image (max 4MB)</p>
                <input type="file" name="featured_image" accept="image/*" id="featured-image-input"
                       class="block mx-auto text-sm font-inter text-on-surface-variant">
            </div>
            @error('featured_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label for="excerpt" class="block font-inter text-sm font-medium text-on-surface mb-2">Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="Short summary for listings and SEO..."
                      class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 outline-none resize-none">{{ old('excerpt', $post->excerpt) }}</textarea>
            @error('excerpt')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-6 card-lift">
            <label for="content" class="block font-inter text-sm font-medium text-on-surface mb-2">Content</label>
            <textarea id="content" name="content" rows="14" placeholder="Write your article content here..."
                      class="w-full rounded-lg border border-outline-variant px-3 py-2.5 font-inter text-sm focus:ring-2 focus:ring-primary-container/30 outline-none resize-y">{{ old('content', $post->content) }}</textarea>
            @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <h3 class="font-manrope font-bold text-[#1c2c40] mb-4">Post Settings</h3>
            <div class="space-y-4">
                <div>
                    <label for="category_slug" class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Category *</label>
                    <select id="category_slug" name="category_slug" required
                            class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                        @foreach($categories as $slug => $label)
                            <option value="{{ $slug }}" {{ old('category_slug', $post->category_slug) === $slug ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category_slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tags" class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Tags</label>
                    <input type="text" id="tags" name="tags" value="{{ $tagsValue }}" placeholder="workspace, tips, trends"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                </div>
                <div>
                    <label for="slug" class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">URL Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="auto-generated-from-title"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <h3 class="font-manrope font-bold text-[#1c2c40] mb-4">Publishing</h3>
            <div class="space-y-4">
                <div>
                    <label for="status" class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Status *</label>
                    <select id="status" name="status" required
                            class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}
                           class="rounded border-outline-variant text-primary-container focus:ring-primary-container/30">
                    <span class="font-inter text-sm text-on-surface">Feature on blog homepage</span>
                </label>
                <div>
                    <label for="author_name" class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Author Name</label>
                    <input type="text" id="author_name" name="author_name"
                           value="{{ old('author_name', $post->getRawOriginal('author_name')) }}"
                           placeholder="{{ auth()->user()->display_name }}"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                </div>
                <div>
                    <label for="author_role" class="block font-inter text-xs font-medium text-on-surface-variant mb-1.5">Author Role</label>
                    <input type="text" id="author_role" name="author_role"
                           value="{{ old('author_role', $post->author_role) }}"
                           placeholder="e.g. Content Editor"
                           class="w-full rounded-lg border border-outline-variant px-3 py-2 font-inter text-sm outline-none focus:ring-2 focus:ring-primary-container/30">
                </div>
                <div class="flex justify-between font-inter text-sm">
                    <span class="text-on-surface-variant">Word count</span>
                    <span class="font-medium text-[#1c2c40]" id="word-count">0</span>
                </div>
                <div class="flex justify-between font-inter text-sm">
                    <span class="text-on-surface-variant">Est. read time</span>
                    <span class="font-medium text-[#1c2c40]" id="read-time">1 min</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 card-lift">
            <h3 class="font-manrope font-bold text-[#1c2c40] mb-3">SEO Preview</h3>
            <div class="rounded-lg border border-outline-variant/50 p-3 bg-surface-container-low/50">
                <p class="font-inter text-sm text-blue-700 font-medium truncate" id="seo-title">{{ $post->title ?: 'Your Post Title' }} — GridSpace Blog</p>
                <p class="font-inter text-xs text-green-700 truncate mt-0.5" id="seo-url">{{ url('/blog/' . ($post->slug ?: 'your-post')) }}</p>
                <p class="font-inter text-xs text-on-surface-variant mt-1 line-clamp-2" id="seo-excerpt">{{ $post->excerpt ?: 'Your excerpt will appear here in search results...' }}</p>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
(function () {
    const title = document.getElementById('title');
    const excerpt = document.getElementById('excerpt');
    const content = document.getElementById('content');
    const wordCountEl = document.getElementById('word-count');
    const readTimeEl = document.getElementById('read-time');
    const seoTitle = document.getElementById('seo-title');
    const seoExcerpt = document.getElementById('seo-excerpt');

    function updateStats() {
        const text = (content?.value || '').trim();
        const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
        const minutes = Math.max(1, Math.ceil(words / 200));
        if (wordCountEl) wordCountEl.textContent = words;
        if (readTimeEl) readTimeEl.textContent = minutes + ' min';
        if (seoTitle && title) seoTitle.textContent = (title.value || 'Your Post Title') + ' — GridSpace Blog';
        if (seoExcerpt && excerpt) seoExcerpt.textContent = excerpt.value || 'Your excerpt will appear here in search results...';
    }

    [title, excerpt, content].forEach(el => el?.addEventListener('input', updateStats));
    updateStats();
})();
</script>
@endpush
