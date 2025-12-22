<x-app-layout>
    <x-page-header 
        title="Job Applications" 
        description="Track and manage your job search progress."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Job Applications'],
        ]"
    >
        <x-slot name="actions">
            <form method="GET" action="{{ route('job-applications.index') }}" class="relative">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search jobs..." 
                    class="w-64 pl-9 pr-3 py-1.5 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow"
                >
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
            <a href="{{ route('job-applications.create') }}" class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Job
            </a>
        </x-slot>
    </x-page-header>

    <!-- Search Filter Chip -->
    @if(request('search'))
        <div class="flex items-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-100 dark:border-primary-800">
                <span>Search: <strong>{{ request('search') }}</strong></span>
                <a href="{{ route('job-applications.index') }}" class="ml-1 hover:text-primary-900 dark:hover:text-primary-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </span>
        </div>
    @endif

    <!-- Modern Table -->
    <x-table-container>
        <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Company & Role</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Stage</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Location</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Salary</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Applied</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($applications as $application)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 shadow-sm">
                                        {{ substr($application->company_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            @if(request('search'))
                                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-200 dark:bg-yellow-900 text-gray-900 dark:text-white rounded-sm px-0.5">$1</mark>', e($application->job_title)) !!}
                                            @else
                                                {{ $application->job_title }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if(request('search'))
                                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-200 dark:bg-yellow-900 text-gray-900 dark:text-white rounded-sm px-0.5">$1</mark>', e($application->company_name)) !!}
                                            @else
                                                {{ $application->company_name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                    {{ $application->stage->name }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">
                                {{ $application->location ?? 'Remote' }}
                            </td>
                            <td class="px-4 py-2.5 text-gray-500 dark:text-gray-400">
                                @if($application->salary_min && $application->salary_max)
                                    ${{ number_format($application->salary_min/1000) }}k - ${{ number_format($application->salary_max/1000) }}k
                                @elseif($application->salary_min)
                                    ${{ number_format($application->salary_min/1000) }}k+
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-xs text-gray-500">
                                {{ $application->applied_at ? $application->applied_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('job-applications.edit', $application) }}" class="p-1 text-gray-400 hover:text-primary-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button 
                                        type="button" 
                                        @click="$dispatch('open-confirm-modal', {
                                            title: 'Delete Application',
                                            message: 'Are you sure you want to delete this application for {{ $application->company_name }}? This action cannot be undone.',
                                            action: '{{ route('job-applications.destroy', $application) }}',
                                            method: 'DELETE'
                                        })"
                                        class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No job applications found. Start by adding one!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
                    {{ $applications->links() }}
                </div>
            @endif
    </x-table-container>
</x-app-layout>
