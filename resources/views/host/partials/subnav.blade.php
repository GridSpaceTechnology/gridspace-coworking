@php
    if (! auth()->user()?->isHost()) {
        return;
    }

    $links = [
        ['route' => 'dashboard', 'label' => 'My Listings', 'icon' => 'apartment'],
        ['route' => 'host.calendar', 'label' => 'Calendar', 'icon' => 'calendar_month'],
        ['route' => 'host.earnings', 'label' => 'Earnings', 'icon' => 'payments'],
        ['route' => 'inquiries.index', 'label' => 'Messages', 'icon' => 'chat'],
        ['route' => 'profile.edit', 'label' => 'Profile', 'icon' => 'person'],
    ];
@endphp

<nav class="mb-6 md:mb-8 -mx-1 overflow-x-auto scrollbar-hide" aria-label="Host navigation">
    <div class="flex gap-2 min-w-max px-1 pb-1">
        @foreach($links as $link)
            @php
                $active = request()->routeIs($link['route']) || ($link['route'] === 'dashboard' && request()->has('add_listing'));
            @endphp
            <a href="{{ route($link['route']) }}"
               @class([
                   'inline-flex items-center gap-2 px-4 py-2 rounded-full font-inter text-sm font-medium whitespace-nowrap transition-colors',
                   'bg-primary-container text-white shadow-sm' => $active,
                   'bg-white border border-outline-variant/60 text-on-surface-variant hover:border-primary-container/40 hover:text-on-surface' => ! $active,
               ])>
                <span class="material-symbols-outlined text-[18px]">{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</nav>
