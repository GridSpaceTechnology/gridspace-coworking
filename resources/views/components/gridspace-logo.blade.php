<!-- Gridspace Logo - Modern Text Design -->
<div {{ $attributes->merge(['class' => 'flex items-center space-x-2']) }}>
    <!-- Grid Icon -->
    <div class="relative">
        <svg class="w-8 h-8" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Grid squares -->
            <rect x="2" y="2" width="6" height="6" rx="1" class="fill-blue-600" />
            <rect x="10" y="2" width="6" height="6" rx="1" class="fill-blue-500" />
            <rect x="18" y="2" width="6" height="6" rx="1" class="fill-blue-400" />
            <rect x="26" y="2" width="4" height="6" rx="1" class="fill-blue-300" />
            
            <rect x="2" y="10" width="6" height="6" rx="1" class="fill-blue-500" />
            <rect x="10" y="10" width="6" height="6" rx="1" class="fill-blue-600" />
            <rect x="18" y="10" width="6" height="6" rx="1" class="fill-blue-500" />
            <rect x="26" y="10" width="4" height="6" rx="1" class="fill-blue-400" />
            
            <rect x="2" y="18" width="6" height="6" rx="1" class="fill-blue-400" />
            <rect x="10" y="18" width="6" height="6" rx="1" class="fill-blue-500" />
            <rect x="18" y="18" width="6" height="6" rx="1" class="fill-blue-600" />
            <rect x="26" y="18" width="4" height="6" rx="1" class="fill-blue-500" />
            
            <rect x="2" y="26" width="6" height="4" rx="1" class="fill-blue-300" />
            <rect x="10" y="26" width="6" height="4" rx="1" class="fill-blue-400" />
            <rect x="18" y="26" width="6" height="4" rx="1" class="fill-blue-500" />
            <rect x="26" y="26" width="4" height="4" rx="1" class="fill-blue-600" />
        </svg>
        
        <!-- Subtle glow effect -->
        <div class="absolute inset-0 bg-blue-400 opacity-20 blur-sm -z-10"></div>
    </div>
    
    <!-- Text -->
    <div class="flex flex-col">
        <span class="text-xl font-bold text-gray-800 leading-tight">Grid</span>
        <span class="text-xl font-bold text-blue-600 leading-tight -mt-1">space</span>
    </div>
</div>
