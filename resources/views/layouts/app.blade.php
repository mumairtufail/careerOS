<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)
    }" 
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
    :class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareerOS') }} - {{ $title ?? 'Dashboard' }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        
        <style>
            [x-cloak] { display: none !important; }
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
        <div 
            x-data="{ 
                sidebarMobileOpen: false
            }" 
            class="min-h-screen flex"
        >
            
            <!-- Sidebar (Fixed) -->
            @include('layouts.partials.sidebar')
            
            <!-- Mobile Sidebar Overlay -->
            <div 
                x-show="sidebarMobileOpen" 
                x-transition.opacity
                @click="sidebarMobileOpen = false"
                class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-40 lg:hidden"
                x-cloak
            ></div>

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-h-screen lg:ml-64 transition-all duration-200">
                <!-- Top Navbar -->
                @include('layouts.partials.navbar')

                <!-- Page Content -->
                <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 pt-24">
                    <div class="max-w-7xl mx-auto w-full">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <x-toast-notifications />
    </body>
</html>
