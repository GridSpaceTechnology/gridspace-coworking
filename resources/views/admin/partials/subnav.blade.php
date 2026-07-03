@php
    if (! auth()->user()?->isAdmin()) {
        return;
    }

    $links = [
        ['route' => 'admin.index', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ['route' => 'admin.listings.index', 'label' => 'Listings', 'icon' => 'apartment'],
        ['route' => 'admin.users.index', 'label' => 'Users', 'icon' => 'group'],
        ['route' => 'admin.bookings.index', 'label' => 'Bookings', 'icon' => 'event_available'],
        ['route' => 'admin.blog.index', 'label' => 'Blog', 'icon' => 'article'],
        ['route' => 'analytics.index', 'label' => 'Analytics', 'icon' => 'analytics'],
    ];
@endphp

<nav class="mb-6 md:mb-8 -mx-1 overflow-x-auto" aria-label="Admin navigation">
    <div class="flex gap-2 min-w-max px-1 pb-1">
        @foreach($links as $link)
            @php
                $active = request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route']));
                if ($link['route'] === 'admin.blog.index') {
                    $active = request()->routeIs('admin.blog.*');
                }
                if ($link['route'] === 'analytics.index') {
                    $active = request()->routeIs('analytics.*');
                }
            @endphp
            <a href="{{ route($link['route']) }}"
               @class([
                   'inline-flex items-center gap-2 px-4 py-2 rounded-full font-inter text-sm font-medium whitespace-nowrap transition-colors',
                   'bg-[#1c2c40] text-white shadow-sm' => $active,
                   'bg-white border border-outline-variant/60 text-on-surface-variant hover:border-[#1c2c40]/30 hover:text-on-surface' => ! $active,
               ])>
                <span class="material-symbols-outlined text-[18px]">{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</nav>
