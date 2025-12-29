<x-app-layout>
    <x-page-header 
        :title="$jobApplication->job_title" 
        :description="$jobApplication->company_name"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Job Applications', 'url' => route('job-applications.index')],
            ['label' => $jobApplication->job_title],
        ]"
    >
        <x-slot name="actions">
            <a href="{{ route('job-applications.edit', $jobApplication) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Edit Application
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Tabs -->
        <div x-data="{ activeTab: 'details' }" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button 
                        @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Details
                        </div>
                    </button>
                    <button 
                        @click="activeTab = 'match'"
                        :class="activeTab === 'match' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Match Analysis
                        </div>
                    </button>
                    <button 
                        @click="activeTab = 'activity'"
                        :class="activeTab === 'activity' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Activity
                        </div>
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Details Tab -->
                <div x-show="activeTab === 'details'" x-cloak>
                    @include('job-applications.partials.details-tab')
                </div>

                <!-- Match Analysis Tab -->
                <div x-show="activeTab === 'match'" x-cloak>
                    @include('job-applications.partials.match-tab')
                </div>

                <!-- Activity Tab -->
                <div x-show="activeTab === 'activity'" x-cloak>
                    @include('job-applications.partials.activity-tab')
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
