<x-app-layout>
    <x-page-header 
        :title="$isEdit ? 'Edit Job Application' : 'Add Job Application'"
        :description="$isEdit ? 'Update application details and status.' : 'Track a new job opportunity.'"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Job Applications', 'url' => route('job-applications.index')],
            ['label' => $isEdit ? 'Edit Application' : 'Add Application'],
        ]"
    />

    <form 
        method="POST" 
            action="{{ $isEdit ? route('job-applications.update', $jobApplication) : route('job-applications.store') }}"
            class="space-y-4"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            
            <!-- Job Details -->
            <x-form-section 
                title="Job Details" 
                description="Key information about the role and company."
            >
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-text-input
                            label="Company Name"
                            name="company_name"
                            :value="old('company_name', $jobApplication->company_name ?? '')"
                            placeholder="e.g. Acme Corp"
                            required
                        />

                        <x-text-input
                            label="Job Title"
                            name="job_title"
                            :value="old('job_title', $jobApplication->job_title ?? '')"
                            placeholder="e.g. Senior Developer"
                            required
                        />
                    </div>

                    <x-text-input
                        label="Job URL"
                        name="job_url"
                        type="url"
                        :value="old('job_url', $jobApplication->job_url ?? '')"
                        placeholder="https://linkedin.com/jobs/..."
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-text-input
                            label="Location"
                            name="location"
                            :value="old('location', $jobApplication->location ?? '')"
                            placeholder="e.g. New York, NY"
                        />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Work Mode</label>
                            <select 
                                name="work_mode" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow"
                            >
                                <option value="">Select Mode</option>
                                <option value="remote" {{ old('work_mode', $jobApplication->work_mode ?? '') == 'remote' ? 'selected' : '' }}>Remote</option>
                                <option value="hybrid" {{ old('work_mode', $jobApplication->work_mode ?? '') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="onsite" {{ old('work_mode', $jobApplication->work_mode ?? '') == 'onsite' ? 'selected' : '' }}>On-site</option>
                            </select>
                        </div>
                    </div>
                </div>
            </x-form-section>

            <!-- Status & Compensation -->
            <x-form-section 
                title="Status & Compensation" 
                description="Track where you are in the process and salary expectations."
            >
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Current Stage</label>
                            <select 
                                name="job_stage_id" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow"
                                required
                            >
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}" {{ old('job_stage_id', $jobApplication->job_stage_id ?? '') == $stage->id ? 'selected' : '' }}>
                                        {{ $stage->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <x-text-input
                            label="Date Applied"
                            name="applied_at"
                            type="date"
                            :value="old('applied_at', $jobApplication->applied_at?->format('Y-m-d') ?? date('Y-m-d'))"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-text-input
                            label="Min Salary"
                            name="salary_min"
                            type="number"
                            :value="old('salary_min', $jobApplication->salary_min ?? '')"
                            placeholder="e.g. 80000"
                        />

                        <x-text-input
                            label="Max Salary"
                            name="salary_max"
                            type="number"
                            :value="old('salary_max', $jobApplication->salary_max ?? '')"
                            placeholder="e.g. 120000"
                        />
                    </div>
                </div>
            </x-form-section>

            <!-- Notes -->
            <x-form-section 
                title="Notes" 
                description="Any additional details or thoughts about this opportunity."
            >
                <textarea 
                    name="notes" 
                    rows="4" 
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow"
                    placeholder="Referral from Mike, tech stack includes Laravel..."
                >{{ old('notes', $jobApplication->notes ?? '') }}</textarea>
            </x-form-section>

            <!-- Activity Timeline (Only in Edit Mode) -->
            @if($isEdit)
                <x-form-section 
                    title="Activity Timeline" 
                    description="History of changes and updates."
                >
                    <x-activity-timeline :activities="$jobApplication->activities" />
                </x-form-section>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('job-applications.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-sm transition-colors">
                    {{ $isEdit ? 'Save Changes' : 'Add Job Application' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
