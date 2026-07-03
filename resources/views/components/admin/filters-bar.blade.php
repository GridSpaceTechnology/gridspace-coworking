@props(['action'])

<form method="GET" action="{{ $action }}" class="px-5 py-4 border-b border-outline-variant/40 bg-white">
  <div class="flex flex-wrap items-end gap-3">
    {{ $slot }}
    <div class="flex items-center gap-2 ml-auto">
      <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#1c2c40] text-white font-inter text-sm font-semibold hover:bg-[#2a3d56] transition-colors">
        <span class="material-symbols-outlined text-[18px]">filter_alt</span>
        Apply
      </button>
      <a href="{{ $action }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-outline-variant font-inter text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition-colors">
        Reset
      </a>
    </div>
  </div>
</form>
