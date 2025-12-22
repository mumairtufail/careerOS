<x-app-layout>
    <x-page-header 
        :title="$resume->title" 
        description="Parsed resume details."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Resumes', 'url' => route('resumes.index')],
            ['label' => $resume->title],
        ]"
    >
        <x-slot name="actions">
            @if($resume->hasFailed() || $resume->isPending())
                <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-500 focus:bg-amber-500 active:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2"
                    x-data
                    x-on:click="$dispatch('show-reparse-modal-show', { id: {{ $resume->id }}, title: '{{ $resume->title }}' })"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Re-parse Resume
                </button>
            @endif
            
            <a href="{{ Storage::url($resume->file_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                Download Original
            </a>
            <button 
                type="button" 
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                x-data
                x-on:click="$dispatch('open-confirm-modal', {
                    title: 'Delete Resume',
                    message: 'Are you sure you want to delete this resume? This action cannot be undone.',
                    action: '{{ route('resumes.destroy', $resume) }}',
                    method: 'DELETE'
                })"
            >
                Delete
            </button>
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Parse Status Banner -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            @if($resume->isParsed())
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @elseif($resume->hasFailed())
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $resume->getStatusLabel() }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                @if($resume->isParsed())
                                    Resume was successfully parsed on {{ $resume->updated_at->format('M d, Y \a\t H:i') }}
                                @elseif($resume->hasFailed())
                                    Parsing failed. You can try to re-parse the resume.
                                @else
                                    Resume is waiting to be parsed.
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $resume->getStatusBadgeClass() }}">
                        {{ $resume->getStatusLabel() }}
                    </span>
                </div>
                
                @if($resume->hasFailed() && $resume->parse_error)
                    <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm font-medium text-red-800 dark:text-red-400">Error Details:</p>
                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ $resume->parse_error }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if(session('warning'))
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            {{ session('warning') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Section -->
        @if($resume->summary)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Professional Summary</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $resume->summary }}</p>
                </div>
            </div>
        @endif

        <!-- Structured Data -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Skills -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Skills</h3>
                    @if($resume->skills && count($resume->skills) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($resume->skills as $skill)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded text-xs font-semibold">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">No skills extracted.</p>
                    @endif
                </div>
            </div>

            <!-- Experience -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Experience</h3>
                        @if($resume->years_of_experience)
                            <span class="text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 px-2 py-1 rounded">
                                {{ $resume->years_of_experience }} Years Total
                            </span>
                        @endif
                    </div>
                    
                    @if($resume->experience && count($resume->experience) > 0)
                        <div class="space-y-4">
                            @foreach($resume->experience as $exp)
                                <div class="border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $exp['role'] ?? 'Role' }}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $exp['company'] ?? 'Company' }} | {{ $exp['duration'] ?? 'Duration' }}</p>
                                    @if(isset($exp['description']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $exp['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">No experience extracted.</p>
                    @endif
                </div>
            </div>

            <!-- Education -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Education</h3>
                    @if($resume->education && count($resume->education) > 0)
                        <div class="space-y-4">
                            @foreach($resume->education as $edu)
                                <div class="border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $edu['institution'] ?? 'Institution' }}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $edu['degree'] ?? 'Degree' }} | {{ $edu['year'] ?? 'Year' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">No education extracted.</p>
                    @endif
                </div>
            </div>

            <!-- Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Projects</h3>
                    @if($resume->projects && count($resume->projects) > 0)
                        <div class="space-y-4">
                            @foreach($resume->projects as $project)
                                <div class="border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $project['name'] ?? 'Project Name' }}</h4>
                                    @if(isset($project['technologies']))
                                        <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">{{ is_array($project['technologies']) ? implode(', ', $project['technologies']) : $project['technologies'] }}</p>
                                    @endif
                                    @if(isset($project['description']))
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">No projects extracted.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Raw Content (Collapsible or at bottom) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Raw Parsed Text</h3>
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg whitespace-pre-wrap text-xs text-gray-600 dark:text-gray-400 font-mono h-48 overflow-y-auto">
                    {{ $resume->parsed_content }}
                </div>
            </div>
        </div>
    </div>

    <x-confirm-modal />

    <!-- Re-parse Modal -->
    <div 
        x-data="{ 
            show: false, 
            resumeId: {{ $resume->id }}, 
            resumeTitle: '{{ $resume->title }}',
            close() { this.show = false; },
            confirm() {
                document.getElementById('reparse-form-show').submit();
            }
        }"
        @show-reparse-modal-show.window="show = true"
        x-show="show"
        x-cloak
        class="relative z-50"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-all transform"
            @click="close()"
        >
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        </div>

        <!-- Modal Panel -->
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="fixed inset-0 z-10 overflow-y-auto"
        >
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-2xl transition-all sm:my-8 border border-gray-100 dark:border-gray-800">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 pt-0.5">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">Re-parse Resume</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to re-parse <strong x-text="resumeTitle"></strong>? This will attempt to extract information from the resume again using AI.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button 
                            type="button" 
                            class="inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-800 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 sm:w-auto transition-colors"
                            @click="close()"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            class="inline-flex w-full justify-center rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 sm:w-auto transition-colors"
                            @click="confirm()"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Re-parse Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Re-parse Form (hidden) -->
    <form 
        id="reparse-form-show" 
        action="{{ route('resumes.re-parse', $resume) }}" 
        method="POST"
        class="hidden"
    >
        @csrf
    </form>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
