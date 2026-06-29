<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    'on-surface-variant': '#5b4038',
                    'surface-container-high': '#e6e8ea',
                    'surface-container-highest': '#e0e3e5',
                    'primary-container': '#ff5a1f',
                    secondary: '#49607e',
                    'surface-variant': '#e0e3e5',
                    'outline-variant': '#e4beb3',
                    'on-surface': '#191c1e',
                    'surface-container-lowest': '#ffffff',
                    surface: '#f7f9fb',
                    'surface-container': '#eceef0',
                    'surface-container-low': '#f2f4f6',
                    primary: '#ae3200',
                    outline: '#8f7067',
                    'primary-fixed': '#ffdbd0',
                    navy: '#0A2540',
                },
                maxWidth: { 'container-max': '1280px' },
                spacing: {
                    'stack-sm': '12px', 'stack-lg': '48px', 'stack-md': '24px',
                    'margin-desktop': '48px', 'margin-mobile': '16px', gutter: '24px',
                },
                fontFamily: {
                    manrope: ['Manrope', 'sans-serif'],
                    inter: ['Inter', 'sans-serif'],
                    mono: ['JetBrains Mono', 'monospace'],
                    sans: ['Manrope', 'sans-serif'],
                },
                borderRadius: { grid: '4px' },
            },
        },
    }
</script>
<style>
    body { background-color: #F8FAFC; font-family: Inter, sans-serif; }
    .card-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(10, 37, 64, 0.05); }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .gradient-overlay { background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%); }

    /* Dark mode – global readability */
    .dark body {
        background-color: #0b0f14;
        color: #e8eaed;
    }
    .dark .text-on-surface,
    .dark .text-on-background,
    .dark .text-\[\#1c2c40\] {
        color: #f1f5f9 !important;
    }
    .dark .text-on-surface-variant,
    .dark .text-secondary {
        color: #94a3b8 !important;
    }
    .dark .text-gray-600,
    .dark .text-gray-700,
    .dark .text-gray-800,
    .dark .text-gray-900 {
        color: #e2e8f0 !important;
    }
    .dark .bg-white,
    .dark .bg-surface-container-lowest {
        background-color: #161b22 !important;
        color: #e8eaed;
    }
    .dark .bg-surface,
    .dark .bg-surface-container-low,
    .dark .bg-surface-container,
    .dark .bg-\[\#fafbfc\] {
        background-color: #111820 !important;
    }
    .dark .bg-surface-container-high,
    .dark .bg-surface-container-highest {
        background-color: #1c2430 !important;
    }
    .dark .border-outline-variant,
    .dark .border-outline-variant\/30,
    .dark .border-outline-variant\/40,
    .dark .border-outline-variant\/50,
    .dark .border-outline-variant\/60,
    .dark .border-gray-100,
    .dark .border-gray-200 {
        border-color: #374151 !important;
    }
    .dark .chat-shell {
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.45);
    }
    .dark .chat-bubble-in {
        background-color: #1e3a5f !important;
        color: #e2e8f0 !important;
    }
    .dark .chat-bubble-out {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
    }
    .dark .chat-convo-active {
        background-color: #1a2744 !important;
    }

    /* Tables */
    .dark table {
        color: #e8eaed;
        border-color: #374151;
    }
    .dark table thead,
    .dark table thead tr,
    .dark .bg-gray-50 {
        background-color: #1f2937 !important;
        color: #f1f5f9 !important;
    }
    .dark table thead th {
        color: #cbd5e1 !important;
        border-color: #4b5563 !important;
    }
    .dark table tbody tr {
        border-color: #374151 !important;
        background-color: #161b22;
    }
    .dark table tbody tr:hover,
    .dark .hover\:bg-gray-50:hover {
        background-color: #1f2937 !important;
    }
    .dark table td,
    .dark table th {
        border-color: #374151 !important;
        color: #e2e8f0 !important;
    }
    .dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
        border-color: #374151 !important;
    }

    /* Form inputs */
    .dark input:not([type="checkbox"]):not([type="radio"]),
    .dark select,
    .dark textarea {
        background-color: #1f2937 !important;
        color: #f1f5f9 !important;
        border-color: #4b5563 !important;
    }
    .dark input::placeholder,
    .dark textarea::placeholder {
        color: #9ca3af !important;
    }

    /* Cards & shadows */
    .dark .shadow-sm,
    .dark .shadow,
    .dark .shadow-lg,
    .dark .shadow-xl {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5) !important;
    }
    .dark .card-lift:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
    }

    /* Status / alert boxes stay readable */
    .dark .bg-green-50 { background-color: #14532d !important; }
    .dark .text-green-800, .dark .text-green-700 { color: #86efac !important; }
    .dark .border-green-200 { border-color: #166534 !important; }
    .dark .bg-red-50 { background-color: #450a0a !important; }
    .dark .text-red-800, .dark .text-red-700, .dark .text-red-600 { color: #fca5a5 !important; }
    .dark .border-red-200 { border-color: #991b1b !important; }
    .dark .bg-amber-50 { background-color: #451a03 !important; }
    .dark .text-amber-700, .dark .text-amber-600 { color: #fcd34d !important; }
    .dark .bg-blue-50 { background-color: #1e3a5f !important; }
    .dark .text-blue-600, .dark .text-blue-800 { color: #93c5fd !important; }
</style>
