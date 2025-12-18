<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareerOS') }} - {{ $title ?? 'Dashboard' }}</title>
        <meta name="description" content="CareerOS Dashboard - Manage your job applications and track your career progress.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-950">
        <div x-data="{ sidebarOpen: true, sidebarMobileOpen: false }" class="min-h-screen">
            
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            
            <!-- Mobile Sidebar Overlay -->
            <div 
                x-show="sidebarMobileOpen" 
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarMobileOpen = false"
                class="fixed inset-0 bg-gray-900/80 z-30 lg:hidden"
            ></div>

            <!-- Main Content Wrapper -->
            <div 
                class="transition-all duration-300 ease-in-out"
                :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'"
            >
                <!-- Top Navbar -->
                @include('layouts.partials.navbar')

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

        <!-- Toast Notifications Container -->
        @if(session('success') || session('error') || session('warning') || session('info'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="fixed bottom-4 right-4 z-50"
        >
            @if(session('success'))
                <div class="flex items-center gap-3 px-4 py-3 bg-success-500 text-white rounded-xl shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 px-4 py-3 bg-danger-500 text-white rounded-xl shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('warning'))
                <div class="flex items-center gap-3 px-4 py-3 bg-warning-500 text-white rounded-xl shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="flex items-center gap-3 px-4 py-3 bg-primary-500 text-white rounded-xl shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif
        </div>
        @endif
    </body>
</html>
