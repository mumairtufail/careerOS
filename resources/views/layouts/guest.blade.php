<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareerOS') }} - {{ $title ?? 'Welcome' }}</title>
        <meta name="description" content="CareerOS - Your automated job hunting companion. Find your dream job faster with AI-powered job search.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-primary-950 flex">
            
            <!-- Left Side - Branding Panel -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <!-- Background pattern -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.05%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                
                <!-- Gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-600/20 via-transparent to-secondary-600/20"></div>
                
                <!-- Floating orbs -->
                <div class="absolute top-20 left-20 w-72 h-72 bg-primary-500/30 rounded-full blur-3xl animate-pulse-slow"></div>
                <div class="absolute bottom-32 right-10 w-96 h-96 bg-secondary-500/20 rounded-full blur-3xl animate-pulse-slow delay-1000"></div>
                <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-accent-500/20 rounded-full blur-3xl animate-pulse-slow delay-500"></div>
                
                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center px-16 xl:px-24">
                    <!-- Logo -->
                    <div class="mb-12">
                        <h1 class="text-4xl font-bold text-white mb-2">
                            <span class="bg-gradient-to-r from-primary-400 via-secondary-400 to-accent-400 bg-clip-text text-transparent">
                                CareerOS
                            </span>
                        </h1>
                        <p class="text-gray-400 text-lg">Your journey to your dream job starts here</p>
                    </div>
                    
                    <!-- Features -->
                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg mb-1">AI-Powered Job Matching</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Get matched with jobs that fit your skills and preferences automatically.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center shadow-lg shadow-secondary-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg mb-1">Smart Application Tracking</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Keep track of all your applications in one organized dashboard.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg shadow-accent-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg mb-1">Analytics & Insights</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">Understand your job search patterns and optimize your strategy.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial -->
                    <div class="mt-16 p-6 bg-white/5 backdrop-blur-lg rounded-2xl border border-white/10">
                        <p class="text-gray-300 italic text-sm leading-relaxed mb-4">
                            "CareerOS helped me land my dream job at a top tech company in just 3 weeks. The AI matching is incredibly accurate!"
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                JD
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">John Doe</p>
                                <p class="text-gray-500 text-xs">Software Engineer at Google</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Auth Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-10">
                        <h1 class="text-3xl font-bold">
                            <span class="bg-gradient-to-r from-primary-400 via-secondary-400 to-accent-400 bg-clip-text text-transparent">
                                CareerOS
                            </span>
                        </h1>
                        <p class="text-gray-400 mt-2">Your journey to your dream job</p>
                    </div>

                    <!-- Auth Card -->
                    <div class="bg-gray-900/50 backdrop-blur-xl rounded-3xl border border-gray-800 p-8 shadow-2xl">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <p class="mt-8 text-center text-gray-500 text-sm">
                        © {{ date('Y') }} CareerOS. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
