<x-app-layout>
    <x-page-header 
        title="Add AI Configuration" 
        description="Add a new AI provider configuration."
    >
        <x-slot name="breadcrumbs">
            <x-breadcrumbs 
                :items="[
                    ['label' => 'Settings', 'url' => '#'],
                    ['label' => 'AI Configuration', 'url' => route('ai-configurations.index')],
                    ['label' => 'Add']
                ]" 
            />
        </x-slot>
    </x-page-header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="p-8">
                <form 
                    action="{{ route('ai-configurations.store') }}" 
                    method="POST" 
                    class="space-y-6"
                    x-data="{
                        provider: 'openai',
                        apiKey: '',
                        model: '',
                        availableModels: [],
                        fetchingModels: false,
                        errorMessage: '',
                        
                        async fetchModels() {
                            if (!this.apiKey) {
                                this.errorMessage = 'Please enter an API key first';
                                return;
                            }
                            
                            if (this.provider !== 'gemini') {
                                this.errorMessage = 'Model fetching is currently only supported for Gemini';
                                return;
                            }
                            
                            this.fetchingModels = true;
                            this.errorMessage = '';
                            this.availableModels = [];
                            
                            try {
                                const response = await fetch('{{ route('ai-configurations.fetch-models') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        provider: this.provider,
                                        api_key: this.apiKey
                                    })
                                });
                                
                                const data = await response.json();
                                
                                if (data.success) {
                                    this.availableModels = data.models;
                                    if (data.models.length > 0) {
                                        this.model = data.models[0].name;
                                    }
                                } else {
                                    this.errorMessage = data.message;
                                }
                            } catch (error) {
                                this.errorMessage = 'Failed to fetch models. Please check your API key.';
                            } finally {
                                this.fetchingModels = false;
                            }
                        }
                    }"
                >
                    @csrf

                    <!-- Provider Selection -->
                    <div>
                        <x-input-label for="provider" value="AI Provider" class="text-base font-semibold mb-3" />
                        <select 
                            id="provider" 
                            name="provider" 
                            x-model="provider"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm"
                        >
                            <option value="openai">OpenAI</option>
                            <option value="anthropic">Anthropic (Claude)</option>
                            <option value="gemini">Google Gemini</option>
                            <option value="ollama">Ollama (Local)</option>
                            <option value="other">Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('provider')" class="mt-2" />
                    </div>

                    <!-- API Key -->
                    <div>
                        <x-input-label for="api_key" value="API Key" class="text-base font-semibold mb-3" />
                        <x-text-input 
                            id="api_key" 
                            name="api_key" 
                            type="password" 
                            x-model="apiKey"
                            :value="old('api_key')" 
                            placeholder="sk-..." 
                            class="w-full"
                        />
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Your API key will be encrypted and stored securely.
                        </p>
                        <x-input-error :messages="$errors->get('api_key')" class="mt-2" />
                    </div>

                    <!-- Fetch Models Button (for Gemini) -->
                    <div x-show="provider === 'gemini' && apiKey" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">
                                    Fetch Available Models
                                </h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Click to automatically fetch the list of models available with your API key
                                </p>
                            </div>
                            <button
                                type="button"
                                @click="fetchModels()"
                                :disabled="fetchingModels"
                                class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg x-show="!fetchingModels" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <svg x-show="fetchingModels" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="fetchingModels ? 'Fetching...' : 'Fetch Models'"></span>
                            </button>
                        </div>
                        
                        <!-- Error Message -->
                        <div x-show="errorMessage" class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <p class="text-sm text-red-700 dark:text-red-300" x-text="errorMessage"></p>
                        </div>
                    </div>

                    <!-- Model Selection -->
                    <div>
                        <x-input-label for="model" value="Model" class="text-base font-semibold mb-3" />
                        
                        <!-- Dynamic Dropdown (when models are fetched) -->
                        <template x-if="availableModels.length > 0">
                            <div>
                                <select 
                                    id="model" 
                                    name="model" 
                                    x-model="model"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm"
                                >
                                    <template x-for="m in availableModels" :key="m.name">
                                        <option :value="m.name" x-text="m.displayName + ' (' + m.name + ')'"></option>
                                    </template>
                                </select>
                                <p class="mt-2 text-sm text-success-600 dark:text-success-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="availableModels.length + ' models available'"></span>
                                </p>
                            </div>
                        </template>
                        
                        <!-- Manual Input (default) -->
                        <template x-if="availableModels.length === 0">
                            <div>
                                <x-text-input 
                                    id="model" 
                                    name="model" 
                                    type="text" 
                                    x-model="model"
                                    :value="old('model')" 
                                    placeholder="e.g. gpt-4, claude-3-opus, gemini-1.5-flash-latest" 
                                    class="w-full"
                                />
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span x-show="provider === 'gemini'"><strong>Tip:</strong> Enter your API key above and click "Fetch Models" to see available options</span>
                                    <span x-show="provider === 'openai'"><strong>Recommended:</strong> gpt-4, gpt-4-turbo, gpt-3.5-turbo</span>
                                    <span x-show="provider === 'anthropic'"><strong>Recommended:</strong> claude-3-opus, claude-3-sonnet, claude-3-haiku</span>
                                    <span x-show="provider === 'ollama'"><strong>Example:</strong> llama2, mistral, codellama</span>
                                </p>
                            </div>
                        </template>
                        
                        <x-input-error :messages="$errors->get('model')" class="mt-2" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a 
                            href="{{ route('ai-configurations.index') }}" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-200 transform hover:scale-105"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
