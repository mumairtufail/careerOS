<div class="space-y-6">
    <!-- Job Information -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Job Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $jobApplication->company_name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $jobApplication->job_title }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $jobApplication->location ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Work Mode</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst($jobApplication->work_mode ?? 'N/A') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Salary Range</label>
                <p class="mt-1 text-gray-900 dark:text-white">
                    @if($jobApplication->salary_min || $jobApplication->salary_max)
                        ${{ number_format($jobApplication->salary_min ?? 0) }} - ${{ number_format($jobApplication->salary_max ?? 0) }}
                    @else
                        Not specified
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Applied Date</label>
                <p class="mt-1 text-gray-900 dark:text-white">{{ $jobApplication->applied_at?->format('M d, Y') ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stage</label>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                          style="background-color: {{ $jobApplication->stage->color ?? '#gray' }}20; color: {{ $jobApplication->stage->color ?? '#gray' }};">
                        {{ $jobApplication->stage->name ?? 'N/A' }}
                    </span>
                </p>
            </div>
            @if($jobApplication->job_url)
            <div>
                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Job Posting</label>
                <p class="mt-1">
                    <a href="{{ $jobApplication->job_url }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">
                        View Original Posting →
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Notes -->
    @if($jobApplication->notes)
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h3>
        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $jobApplication->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Job Description -->
    @if($jobApplication->job_description)
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Job Description</h3>
        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 max-h-96 overflow-y-auto">
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $jobApplication->job_description }}</p>
        </div>
    </div>
    @else
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Job Description</h3>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                No job description added yet. Add one to enable Resume Match Analysis.
            </p>
            <a href="{{ route('job-applications.edit', $jobApplication) }}" class="mt-2 inline-flex items-center text-sm font-medium text-yellow-600 dark:text-yellow-400 hover:underline">
                Add Job Description →
            </a>
        </div>
    </div>
    @endif
</div>
