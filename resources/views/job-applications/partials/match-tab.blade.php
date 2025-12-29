<div class="space-y-6">
    @if(!$jobApplication->job_description)
        <!-- No Job Description -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Job Description Required</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a job description to enable AI-powered resume matching analysis.</p>
            <div class="mt-6">
                <a href="{{ route('job-applications.edit', $jobApplication) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 transition">
                    Add Job Description
                </a>
            </div>
        </div>
    @elseif($resumes->isEmpty())
        <!-- No Resumes -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No Resumes Available</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload and parse a resume first to analyze the match.</p>
            <div class="mt-6">
                <a href="{{ route('resumes.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 transition">
                    Upload Resume
                </a>
            </div>
        </div>
    @else
        <!-- Match Analysis Interface -->
        <div x-data="{ selectedResume: '{{ $resumes->first()->id }}', analyzing: false }">
            <!-- Resume Selector -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Resume to Analyze</label>
                <select x-model="selectedResume" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    @foreach($resumes as $resume)
                        <option value="{{ $resume->id }}">{{ $resume->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Analyze Button -->
            <form method="POST" action="{{ route('job-applications.analyze-match', $jobApplication) }}" @submit="analyzing = true">
                @csrf
                <input type="hidden" name="resume_id" :value="selectedResume">
                <input type="hidden" name="force" value="1">
                <button type="submit" :disabled="analyzing" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    <svg x-show="analyzing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="analyzing ? 'Analyzing...' : '{{ $jobApplication->latestMatch ? 'Re-analyze Match' : 'Analyze Match' }}'"></span>
                </button>
            </form>

            <!-- Match Results -->
            @if($jobApplication->latestMatch)
                <div class="mt-8 space-y-6">
                    <!-- Match Score -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Match Score</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Based on {{ $jobApplication->latestMatch->resume->title }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    Analyzed {{ $jobApplication->latestMatch->created_at->diffForHumans() }} 
                                    via {{ ucfirst($jobApplication->latestMatch->ai_provider) }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="relative inline-flex items-center justify-center w-24 h-24">
                                    <svg class="transform -rotate-90 w-24 h-24">
                                        <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200 dark:text-gray-700"/>
                                        <circle cx="48" cy="48" r="40" 
                                                stroke="currentColor" 
                                                stroke-width="8" 
                                                fill="transparent"
                                                stroke-dasharray="{{ 2 * 3.14159 * 40 }}"
                                                stroke-dashoffset="{{ 2 * 3.14159 * 40 * (1 - $jobApplication->latestMatch->match_score / 100) }}"
                                                class="{{ $jobApplication->latestMatch->getScoreColorClass() }}"
                                                stroke-linecap="round"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-2xl font-bold {{ $jobApplication->latestMatch->getScoreColorClass() }}">
                                            {{ $jobApplication->latestMatch->match_score }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Strengths -->
                    @if(!empty($jobApplication->latestMatch->strengths))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Strengths
                        </h3>
                        <ul class="space-y-2">
                            @foreach($jobApplication->latestMatch->strengths as $strength)
                                <li class="flex items-start gap-3 bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $strength }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Gaps -->
                    @if(!empty($jobApplication->latestMatch->gaps))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Areas to Improve
                        </h3>
                        <ul class="space-y-2">
                            @foreach($jobApplication->latestMatch->gaps as $gap)
                                <li class="flex items-start gap-3 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $gap }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- AI Feedback -->
                    @if($jobApplication->latestMatch->ai_feedback)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            AI Recommendations
                        </h3>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $jobApplication->latestMatch->ai_feedback }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="mt-8 text-center py-12 bg-gray-50 dark:bg-gray-900/50 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No Analysis Yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click "Analyze Match" above to get AI-powered insights.</p>
                </div>
            @endif
        </div>
    @endif
</div>
