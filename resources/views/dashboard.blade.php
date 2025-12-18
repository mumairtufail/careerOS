<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Welcome back, {{ Auth::user()->name ?? 'User' }}! 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Here's what's happening with your job search today.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Report
                </button>
                <button class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Application
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Applications -->
        <div class="card p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Applications</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">48</p>
                    <p class="mt-2 flex items-center text-sm text-success-600 dark:text-success-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        +12% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Interviews Scheduled -->
        <div class="card p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Interviews Scheduled</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">5</p>
                    <p class="mt-2 flex items-center text-sm text-success-600 dark:text-success-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        +2 this week
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-secondary-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Response Rate -->
        <div class="card p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Response Rate</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">24%</p>
                    <p class="mt-2 flex items-center text-sm text-warning-600 dark:text-warning-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        Same as last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 rounded-2xl flex items-center justify-center shadow-lg shadow-accent-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Saved Jobs -->
        <div class="card p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saved Jobs</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">23</p>
                    <p class="mt-2 flex items-center text-sm text-danger-600 dark:text-danger-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        -3 applied this week
                    </p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-danger-500 to-danger-600 rounded-2xl flex items-center justify-center shadow-lg shadow-danger-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Applications -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Applications</h3>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">View all</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    <!-- Application Item -->
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                G
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Senior Laravel Developer</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Google • Mountain View, CA</p>
                            </div>
                            <div class="text-right">
                                <span class="badge-success">Interview</span>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">2 days ago</p>
                            </div>
                        </div>
                    </div>

                    <!-- Application Item -->
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                M
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Full Stack Engineer</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Meta • Remote</p>
                            </div>
                            <div class="text-right">
                                <span class="badge-warning">Under Review</span>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">5 days ago</p>
                            </div>
                        </div>
                    </div>

                    <!-- Application Item -->
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                S
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Backend Developer</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Spotify • New York, NY</p>
                            </div>
                            <div class="text-right">
                                <span class="badge-primary">Applied</span>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">1 week ago</p>
                            </div>
                        </div>
                    </div>

                    <!-- Application Item -->
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                A
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">PHP Developer</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amazon • Seattle, WA</p>
                            </div>
                            <div class="text-right">
                                <span class="badge-danger">Rejected</span>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">2 weeks ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="space-y-6">
            <!-- Upcoming Interviews -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upcoming Interviews</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-primary-50 dark:bg-primary-900/20 rounded-xl">
                        <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                            20
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Google - Final Round</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dec 20, 2024 • 2:00 PM</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-bold">
                            22
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Netflix - Tech Screen</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dec 22, 2024 • 10:00 AM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button class="w-full flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-left">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Search Jobs</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Find new opportunities</p>
                        </div>
                    </button>
                    <button class="w-full flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-left">
                        <div class="w-10 h-10 bg-secondary-100 dark:bg-secondary-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Update Resume</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Keep your CV fresh</p>
                        </div>
                    </button>
                    <button class="w-full flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-left">
                        <div class="w-10 h-10 bg-accent-100 dark:bg-accent-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Network</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Connect with recruiters</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
