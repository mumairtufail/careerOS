<x-app-layout>
    <x-page-header 
        title="Upload Resume" 
        description="Upload and parse your resume with AI-powered extraction."
    >
        <x-slot name="breadcrumbs">
            <x-breadcrumbs 
                :items="[
                    ['label' => 'Resumes', 'url' => route('resumes.index')],
                    ['label' => 'Upload']
                ]" 
            />
        </x-slot>
    </x-page-header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Upload Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-8">
                <form 
                    action="{{ route('resumes.store') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                    x-data="{ 
                        submitting: false,
                        fileName: '',
                        fileSize: '',
                        dragOver: false,
                        previewFile(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                this.fileSize = this.formatBytes(file.size);
                            }
                        },
                        formatBytes(bytes) {
                            if (bytes === 0) return '0 Bytes';
                            const k = 1024;
                            const sizes = ['Bytes', 'KB', 'MB'];
                            const i = Math.floor(Math.log(bytes) / Math.log(k));
                            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                        },
                        handleDrop(event) {
                            this.dragOver = false;
                            const file = event.dataTransfer.files[0];
                            if (file) {
                                document.getElementById('resume').files = event.dataTransfer.files;
                                this.fileName = file.name;
                                this.fileSize = this.formatBytes(file.size);
                            }
                        }
                    }"
                    @submit="submitting = true"
                    class="space-y-8"
                >
                    @csrf

                    <!-- Resume Title -->
                    <div>
                        <x-input-label for="title" value="Resume Title" class="text-base font-semibold mb-3" />
                        <x-text-input 
                            id="title" 
                            name="title" 
                            type="text"
                            :value="old('title')" 
                            required 
                            autofocus 
                            placeholder="e.g., Senior Laravel Developer Resume 2024"
                            class="w-full"
                        />
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Give your resume a descriptive title to easily identify it later.
                        </p>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- File Upload Area -->
                    <div>
                        <x-input-label for="resume" value="Resume File" class="text-base font-semibold mb-3" />
                        
                        <!-- Drag & Drop Zone -->
                        <div 
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="handleDrop"
                            :class="{ 'border-primary-500 bg-primary-50 dark:bg-primary-900/20': dragOver }"
                            class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition-all duration-200 hover:border-primary-400 dark:hover:border-primary-500"
                        >
                            <input 
                                id="resume" 
                                type="file" 
                                name="resume" 
                                @change="previewFile"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                required 
                                accept=".pdf,.doc,.docx,.txt"
                            >
                            
                            <!-- Upload Icon & Text -->
                            <div x-show="!fileName" class="space-y-4">
                                <div class="mx-auto w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-medium text-gray-700 dark:text-gray-200">
                                        <span class="text-primary-600 dark:text-primary-400 font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        PDF, DOC, DOCX, or TXT (max 2MB)
                                    </p>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div x-show="fileName" class="flex items-center justify-center gap-4" x-cloak>
                                <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <svg class="w-10 h-10 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="fileName"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="fileSize"></p>
                                    </div>
                                    <button 
                                        type="button" 
                                        @click="fileName = ''; fileSize = ''; document.getElementById('resume').value = ''"
                                        class="ml-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('resume')" class="mt-2" />
                    </div>

                    <!-- AI Extraction Info -->
                    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900/20 dark:to-secondary-900/20 border border-primary-200 dark:border-primary-800 rounded-xl p-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                    AI-Powered Extraction
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Our AI will automatically extract key information including skills, experience, education, projects, and certifications from your resume.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a 
                            href="{{ route('resumes.index') }}" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Resumes
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200 transform hover:scale-105 disabled:opacity-75 disabled:cursor-not-allowed disabled:transform-none"
                            :disabled="submitting"
                        >
                            <svg
                                x-show="submitting"
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                x-cloak
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg x-show="!submitting" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span x-text="submitting ? 'Processing...' : 'Upload & Parse Resume'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Supported Formats</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Upload PDF, DOC, DOCX, or TXT files up to 2MB in size.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Smart Parsing</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">AI extracts skills, experience, education, and more automatically.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 bg-accent-100 dark:bg-accent-900/30 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Secure Storage</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Your resume is securely stored and only accessible to you.</p>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
