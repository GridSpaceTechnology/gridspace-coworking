<button
    type="button"
    id="theme-toggle"
    onclick="toggleTheme()"
    class="{{ $class ?? 'w-10 h-10 flex items-center justify-center rounded-full transition-colors' }} {{ $btnClass ?? 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600' }}"
    aria-label="Toggle light and dark mode"
    title="Toggle theme"
>
    <span class="material-symbols-outlined text-[22px] text-gray-900 dark:text-yellow-300 hidden dark:inline">light_mode</span>
    <span class="material-symbols-outlined text-[22px] text-gray-900 dark:hidden">dark_mode</span>
</button>

<script>
    if (typeof window.toggleTheme !== 'function') {
        window.toggleTheme = function () {
            const html = document.documentElement;
            const isDark = !html.classList.contains('dark');
            html.classList.toggle('dark', isDark);
            html.classList.toggle('light', !isDark);
            html.style.colorScheme = isDark ? 'dark' : 'light';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        };
    }
</script>
