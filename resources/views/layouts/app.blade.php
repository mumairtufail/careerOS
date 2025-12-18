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
        <meta name="description" content="CareerOS Dashboard - Manage your job applications and track your career progress.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Dark Mode & Sidebar State Script (prevents flash) -->
        <script>
            // Dark mode
            if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        
        <style>
            /* Prevent layout shift during page load */
            [x-cloak] { display: none !important; }
            
            /* Smooth page transitions */
            .page-transition {
                animation: fadeIn 0.2s ease-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(4px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 transition-colors duration-200">
        <div 
            x-data="{ 
                sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
                sidebarMobileOpen: false,
                init() {
                    this.$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val));
                }
            }" 
            class="min-h-screen"
        >
            
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            
            <!-- Mobile Sidebar Overlay -->
            <div 
                x-show="sidebarMobileOpen" 
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarMobileOpen = false"
                class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-30 lg:hidden"
                x-cloak
            ></div>

            <!-- Main Content Wrapper -->
            <div 
                class="min-h-screen transition-[margin] duration-300 ease-out"
                :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'"
            >
                <!-- Top Navbar -->
                @include('layouts.partials.navbar')

                <!-- Page Content Container -->
                <div class="page-transition">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="pt-20 pb-4 px-4 sm:px-6 lg:px-8">
                            <div class="max-w-7xl mx-auto">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="px-4 sm:px-6 lg:px-8 py-6 {{ !isset($header) ? 'pt-24' : '' }}">
                        <div class="max-w-7xl mx-auto">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Toast Notifications Component -->
        <x-toast-notifications />
    </body>
</html>
