@php
    use App\Support\Breadcrumbs;
    $overrideLabel = View::hasSection('breadcrumb_label') ? trim(View::getSection('breadcrumb_label')) : null;
    $crumbs = Breadcrumbs::resolve($overrideLabel);
    $backUrl = Breadcrumbs::backUrl();
@endphp

@if(\App\Support\Breadcrumbs::shouldShowToolbar())
<div class="w-full max-w-container-max mx-auto px-4 md:px-margin-desktop mb-6 md:mb-8 {{ View::hasSection('full_width') ? 'pt-4' : '' }}">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2 md:gap-3 min-w-0">
            <a href="{{ $backUrl }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-outline-variant dark:border-gray-600 bg-white dark:bg-gray-800 text-on-surface dark:text-gray-100 hover:bg-surface-container-low dark:hover:bg-gray-700 transition-colors font-inter text-sm font-semibold shrink-0"
               title="Go back">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                <span class="hidden sm:inline">Back</span>
            </a>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant dark:border-gray-600 bg-white dark:bg-gray-800 text-primary-container hover:bg-primary-fixed dark:hover:bg-gray-700 transition-colors shrink-0"
               title="Dashboard">
                <span class="material-symbols-outlined text-xl">dashboard</span>
            </a>

            <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 min-w-0 font-inter text-sm">
                @foreach($crumbs as $index => $crumb)
                    @if($index > 0)
                        <span class="material-symbols-outlined text-base text-on-surface-variant dark:text-gray-500 shrink-0">chevron_right</span>
                    @endif
                    @if($crumb['url'])
                        <a href="{{ $crumb['url'] }}" class="text-secondary dark:text-gray-300 hover:text-primary-container dark:hover:text-primary-container transition-colors truncate">
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="font-semibold text-on-surface dark:text-gray-100 truncate">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="shrink-0">
            @include('layouts.partials.theme-toggle', [
                'btnClass' => 'bg-surface-container dark:bg-gray-800 hover:bg-surface-variant dark:hover:bg-gray-700 border border-outline-variant dark:border-gray-600',
            ])
        </div>
    </div>
</div>
@endif
