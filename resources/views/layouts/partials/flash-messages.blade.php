@if(session('success'))
    <div class="bg-green-50 dark:bg-green-950 border-b border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 text-sm text-center font-inter">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-50 dark:bg-red-950 border-b border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 text-sm text-center font-inter">{{ session('error') }}</div>
@endif
