<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1A3165">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    {{-- Global Laravel object for JavaScript - Defer to avoid blocking --}}
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            user: @json(auth()->user()?->load('roles')) // Restored roles for notifications
        };
    </script>

    {{-- AOS is now loaded locally via Vite --}}


    <!-- Defer non-critical scripts to avoid blocking render -->
    <script src="{{ asset('js/sidebar-toggle.js') }}" defer></script> 

    <!-- Move sidebar styles to CSS file - only keep critical styles inline -->
    <style>
        /* Only critical sidebar styles for initial render */
        #side-nav-bar { transition: width 0.3s ease-in-out; }
        #content { transition: width 0.3s ease-in-out; }
    </style>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Critical CSS only - load the rest asynchronously -->
        <style>
            /* Critical styles for above-the-fold content */
            * { box-sizing: border-box; }
            body { margin: 0; font-family: Nunito, ui-sans-serif, system-ui, sans-serif; }
            .min-h-screen { min-height: 100vh; }
            .flex { display: flex; }
            .flex-col { flex-direction: column; }
            .items-center { align-items: center; }
            .justify-center { justify-content: center; }
            .bg-\[\#1A3165\] { background-color: #1A3165; }
            .text-white { color: white; }
            .p-4 { padding: 1rem; }
            .h-\[80px\] { height: 80px; }
        </style>
        
        <!-- Load optimized CSS asynchronously -->
        <link rel="preload" href="{{ asset('build/assets/app-FnQM53eQ.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="{{ asset('build/assets/app-FnQM53eQ.css') }}"></noscript>
        
        <!-- Load sidebar CSS asynchronously -->
        <link rel="preload" href="{{ asset('css/sidebar.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"></noscript>
    @endif
    {{-- AOS is initialized in app.js --}}
</head>
