@if(isset($paginator) && $paginator->hasPages())
    <div class="px-5 py-4 border-t border-outline-variant/40 bg-surface-container-low/30">
        {{ $paginator->withQueryString()->links() }}
    </div>
@endif
