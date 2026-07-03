@extends('layouts.admin')

@section('title', 'Blog | GridSpace')

@section('admin_content')
<section class="mb-6 md:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] tracking-tight">Blog</h1>
            <p class="font-inter text-sm text-on-surface-variant mt-1">Create and manage blog posts for the GridSpace community</p>
        </div>
        <a href="{{ route('admin.blog.create') }}"
           class="inline-flex items-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors shrink-0">
            <span class="material-symbols-outlined text-[20px]">add</span>
            New Post
        </a>
    </div>
</section>

<div class="grid grid-cols-3 gap-4 md:gap-gutter mb-8">
    @foreach([
        ['label' => 'Total Posts', 'value' => $stats['total']],
        ['label' => 'Published', 'value' => $stats['published']],
        ['label' => 'Total Views', 'value' => number_format($stats['total_views'])],
    ] as $stat)
        <div class="bg-white border border-outline-variant/60 rounded-2xl p-5 text-center card-lift">
            <p class="font-manrope text-3xl font-bold text-[#1c2c40]">{{ $stat['value'] }}</p>
            <p class="font-inter text-xs text-on-surface-variant mt-1 uppercase tracking-wide">{{ $stat['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="bg-white border border-outline-variant/60 rounded-2xl overflow-hidden card-lift">
    @if($posts->isEmpty())
        <div class="p-16 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-outline">menu_book</span>
            </div>
            <h3 class="font-manrope text-lg font-bold text-[#1c2c40] mb-2">No blog posts yet</h3>
            <p class="font-inter text-sm text-on-surface-variant mb-6">Create your first post to engage the community.</p>
            <a href="{{ route('admin.blog.create') }}"
               class="inline-flex items-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">add</span>
                New Post
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-outline-variant/40 bg-surface-container-low/50">
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Title</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Category</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase">Last Updated</th>
                        <th class="px-5 py-3 font-inter text-xs font-semibold text-on-surface-variant uppercase text-right">Activity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($posts as $post)
                        @php
                            $statusClass = $post['status'] === 'published'
                                ? 'bg-blue-100 text-blue-800'
                                : 'bg-amber-100 text-amber-800';
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-inter text-sm font-medium text-[#1c2c40] max-w-xs truncate">{{ $post['title'] }}</p>
                            </td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant">{{ $post['category'] }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $statusClass }}">
                                    {{ $post['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-inter text-sm text-on-surface-variant whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($post['updated_at'])->format('jS M, Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors"
                                            title="Preview"
                                            onclick="openPostModal({{ json_encode($post) }})">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                    <a href="{{ route('blog.index') }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors"
                                       title="View on site">
                                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                    </a>
                                    <a href="{{ route('admin.blog.create') }}"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors"
                                       title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div id="post-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closePostModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto pointer-events-auto shadow-2xl">
            <img id="post-modal-image" src="" alt="" class="w-full h-40 object-cover">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span id="post-modal-status" class="px-2 py-0.5 rounded-full text-[11px] font-semibold"></span>
                    <span class="font-inter text-xs text-on-surface-variant" id="post-modal-author"></span>
                </div>
                <h2 class="font-manrope text-xl font-bold text-[#1c2c40] mb-3" id="post-modal-title"></h2>
                <p class="font-inter text-sm text-on-surface-variant mb-4" id="post-modal-excerpt"></p>
                <p class="font-inter text-xs text-on-surface-variant mb-6">
                    <span id="post-modal-category"></span> · <span id="post-modal-views"></span> views
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('admin.blog.create') }}"
                       class="flex-1 text-center py-2.5 rounded-lg bg-primary-container text-white font-inter text-sm font-semibold hover:bg-primary">Edit</a>
                    <button type="button" onclick="closePostModal()"
                            class="flex-1 py-2.5 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openPostModal(post) {
    document.getElementById('post-modal-title').textContent = post.title;
    document.getElementById('post-modal-excerpt').textContent = post.excerpt;
    document.getElementById('post-modal-author').textContent = post.author_name || 'GridSpace';
    document.getElementById('post-modal-category').textContent = post.category;
    document.getElementById('post-modal-views').textContent = (post.views || 0).toLocaleString();
    document.getElementById('post-modal-image').src = post.image || '';
    const badge = document.getElementById('post-modal-status');
    badge.textContent = post.status;
    badge.className = 'px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize ' +
        (post.status === 'published' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800');
    document.getElementById('post-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePostModal() {
    document.getElementById('post-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
@endpush
@endsection
