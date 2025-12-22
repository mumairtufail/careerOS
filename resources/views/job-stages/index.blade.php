<x-app-layout>
    <x-page-header 
        title="Pipeline Stages" 
        description="Manage the stages of your job application process."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pipeline Stages'],
        ]"
    >
        <x-slot name="actions">
            <a href="{{ route('job-stages.create') }}" class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Stage
            </a>
        </x-slot>
    </x-page-header>

    <!-- Stages List -->
    <x-table-container>
        <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400 w-16 text-center">Order</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Stage Name</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400">Type</th>
                            <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($stages as $stage)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-2.5 text-center text-gray-500">
                                {{ $stage->sort_order }}
                            </td>
                            <td class="px-4 py-2.5 font-medium text-gray-900 dark:text-white">
                                {{ $stage->name }}
                            </td>
                            <td class="px-4 py-2.5">
                                @if($stage->is_system_default)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        System Default
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        Custom
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                @if(!$stage->is_system_default)
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('job-stages.edit', $stage) }}" class="p-1 text-gray-400 hover:text-primary-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button 
                                            type="button" 
                                            @click="$dispatch('open-confirm-modal', {
                                                title: 'Delete Stage',
                                                message: 'Are you sure you want to delete the stage \'{{ $stage->name }}\'? This action cannot be undone.',
                                                action: '{{ route('job-stages.destroy', $stage) }}',
                                                method: 'DELETE'
                                            })"
                                            class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Locked</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No stages found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
    </x-table-container>
</x-app-layout>
