<x-app-layout>
    <x-page-header 
        title="Edit AI Configuration" 
        description="Update your AI provider configuration."
    >
        <x-slot name="breadcrumbs">
            <x-breadcrumbs 
                :items="[
                    ['label' => 'Settings', 'url' => '#'],
                    ['label' => 'AI Configuration', 'url' => route('ai-configurations.index')],
                    ['label' => 'Edit', 'url' => '#']
                ]" 
            />
        </x-slot>
    </x-page-header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form action="{{ route('ai-configurations.update', $aiConfiguration) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="provider" :value="__('Provider')" />
                        <select id="provider" name="provider" class="block mt-1 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                            <option value="openai" {{ $aiConfiguration->provider === 'openai' ? 'selected' : '' }}>OpenAI</option>
                            <option value="anthropic" {{ $aiConfiguration->provider === 'anthropic' ? 'selected' : '' }}>Anthropic</option>
                            <option value="gemini" {{ $aiConfiguration->provider === 'gemini' ? 'selected' : '' }}>Gemini</option>
                            <option value="ollama" {{ $aiConfiguration->provider === 'ollama' ? 'selected' : '' }}>Ollama</option>
                            <option value="other" {{ !in_array($aiConfiguration->provider, ['openai', 'anthropic', 'gemini', 'ollama']) ? 'selected' : '' }}>Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('provider')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="api_key" :value="__('API Key')" />
                        <x-text-input id="api_key" class="block mt-1 w-full" type="password" name="api_key" :value="$aiConfiguration->api_key" placeholder="sk-..." />
                        <x-input-error :messages="$errors->get('api_key')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="model" :value="__('Model')" />
                        <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="$aiConfiguration->model" placeholder="e.g. gpt-4, claude-3-opus, gemini-1.5-flash-latest" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Recommended: <strong>gemini-1.5-flash-latest</strong> or <strong>gemini-1.5-pro-latest</strong> (Gemini), <strong>gpt-4</strong> (OpenAI), <strong>claude-3-opus</strong> (Anthropic)
                        </p>
                        <x-input-error :messages="$errors->get('model')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4">
                            {{ __('Update Configuration') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
