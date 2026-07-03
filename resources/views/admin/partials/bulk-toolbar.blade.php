@props(['bulkAction', 'bulkLabel' => 'Delete Selected'])

<div class="px-5 py-3 border-b border-outline-variant/40 bg-surface-container-low/30 flex flex-wrap items-center justify-between gap-3">
  <div class="flex items-center gap-3">
    <label class="inline-flex items-center gap-2 font-inter text-sm text-on-surface-variant cursor-pointer">
      <input type="checkbox" class="bulk-select-all rounded border-outline-variant text-primary-container focus:ring-primary-container/30" data-bulk-table="{{ $bulkAction }}">
      Select all
    </label>
    <button type="submit"
            class="bulk-delete-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-300 text-red-600 font-inter text-xs font-semibold hover:bg-red-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
            disabled
            onclick="return confirm('Delete selected items? This cannot be undone.');">
      <span class="material-symbols-outlined text-[16px]">delete</span>
      {{ $bulkLabel }}
    </button>
  </div>
  @if(isset($paginator))
    <p class="font-inter text-xs text-on-surface-variant">
      Showing {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}
    </p>
  @endif
</div>

@once
  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.bulk-select-all').forEach(master => {
      const table = master.closest('form')?.querySelector('table');
      if (!table) return;

      const rowChecks = () => table.querySelectorAll('.bulk-row-check');
      const deleteBtn = master.closest('form')?.querySelector('.bulk-delete-btn');

      const sync = () => {
        const checks = rowChecks();
        const checked = [...checks].filter(c => c.checked).length;
        if (deleteBtn) deleteBtn.disabled = checked === 0;
        master.indeterminate = checked > 0 && checked < checks.length;
        master.checked = checks.length > 0 && checked === checks.length;
      };

      master.addEventListener('change', () => {
        rowChecks().forEach(c => { c.checked = master.checked; });
        sync();
      });

      rowChecks().forEach(c => c.addEventListener('change', sync));
    });
  });
  </script>
  @endpush
@endonce
