<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <!-- Breadcrumbs -->
                <x-breadcrumbs :items="[
                    ['label' => 'Users', 'url' => route('users.index')],
                    ['label' => $isEdit ? 'Edit User' : 'New User'],
                ]" class="mb-2" />
                
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $isEdit ? 'Edit User' : 'Create New User' }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $isEdit ? 'Update user information and credentials' : 'Add a new user to the system' }}
                </p>
            </div>
            <a href="{{ route('users.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form 
            method="POST" 
            action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}"
            x-data="{ submitting: false }"
            @submit="submitting = true"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- User Information Card -->
            <div class="card p-6 mb-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Information</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Basic user details</p>
                </div>

                <div class="space-y-6">
                    <!-- Name -->
                    <x-text-input 
                        name="name" 
                        label="Full Name" 
                        placeholder="John Doe"
                        :value="$user->name ?? ''"
                        :required="true"
                    >
                        <x-slot name="icon">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </x-slot>
                    </x-text-input>

                    <!-- Email -->
                    <x-text-input 
                        name="email" 
                        type="email"
                        label="Email Address" 
                        placeholder="john@example.com"
                        :value="$user->email ?? ''"
                        :required="true"
                    >
                        <x-slot name="icon">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </x-slot>
                    </x-text-input>
                </div>
            </div>

            <!-- Password Card -->
            <div class="card p-6 mb-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $isEdit ? 'Change Password' : 'Set Password' }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $isEdit ? 'Leave blank to keep current password' : 'Set a secure password for the user' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <x-text-input 
                        name="password" 
                        type="password"
                        label="Password" 
                        placeholder="••••••••"
                        hint="Minimum 8 characters"
                        :required="!$isEdit"
                    >
                        <x-slot name="icon">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </x-slot>
                    </x-text-input>

                    <!-- Confirm Password -->
                    <x-text-input 
                        name="password_confirmation" 
                        type="password"
                        label="Confirm Password" 
                        placeholder="••••••••"
                        :required="!$isEdit"
                    >
                        <x-slot name="icon">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </x-slot>
                    </x-text-input>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('users.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="btn-primary"
                    :disabled="submitting"
                    :class="{ 'opacity-75 cursor-not-allowed': submitting }"
                >
                    <!-- Loading Spinner -->
                    <svg 
                        x-show="submitting" 
                        class="animate-spin -ml-1 mr-2 h-4 w-4" 
                        fill="none" 
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg x-show="!submitting" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="submitting ? 'Saving...' : '{{ $isEdit ? 'Update User' : 'Create User' }}'"></span>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
