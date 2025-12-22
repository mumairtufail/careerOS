<x-app-layout>
    <x-page-header 
        :title="$isEdit ? 'Edit Stage' : 'Add Stage'"
        :description="$isEdit ? 'Update pipeline stage details.' : 'Create a new stage for your pipeline.'"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Pipeline Stages', 'url' => route('job-stages.index')],
            ['label' => $isEdit ? 'Edit Stage' : 'Add Stage'],
        ]"
    />

    <form 
        method="POST" 
            action="{{ $isEdit ? route('job-stages.update', $jobStage) : route('job-stages.store') }}"
            class="space-y-4"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            
            <x-form-section 
                title="Stage Details" 
                description="Define the name and order of this stage."
            >
                <div class="space-y-4">
                    <x-text-input
                        label="Stage Name"
                        name="name"
                        :value="old('name', $jobStage->name ?? '')"
                        placeholder="e.g. Phone Screen"
                        required
                    />

                    <x-text-input
                        type="number"
                        label="Sort Order"
                        name="sort_order"
                        :value="old('sort_order', $jobStage->sort_order ?? 0)"
                        placeholder="e.g. 10"
                        required
                        min="0"
                    />
                </div>
            </x-form-section>

            <div class="flex justify-end gap-3">
                <a 
                    href="{{ route('job-stages.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                >
                    Cancel
                </a>
                <button 
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm shadow-primary-500/20"
                >
                    {{ $isEdit ? 'Update Stage' : 'Create Stage' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
