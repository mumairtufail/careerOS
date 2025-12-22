<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    {{ $isEdit ? 'Edit User' : 'Create User' }}
                </h1>
                <p class="text-sm text-gray-500">
                    {{ $isEdit ? 'Update user information and permissions.' : 'Add a new team member to the workspace.' }}
                </p>
            </div>
            <a href="{{ route('users.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                ← Back to Users
            </a>
        </div>

        <form 
            method="POST" 
            action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}"
            class="space-y-8"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            
            <!-- Account Details Section -->
            <x-form-section 
                title="Account Details" 
                description="Basic information for the user account. They will receive an email to verify their address."
            >
                <div class="space-y-4">
                    <x-text-input
                        label="Full Name"
                        name="name"
                        :value="old('name', $user->name ?? '')"
                        placeholder="e.g. John Doe"
                        required
                    />

                    <x-text-input
                        label="Email Address"
                        name="email"
                        type="email"
                        :value="old('email', $user->email ?? '')"
                        placeholder="john@company.com"
                        required
                    />
                </div>
            </x-form-section>

            <!-- Security Section -->
            <x-form-section 
                title="Security" 
                description="Manage the user's password and security settings."
            >
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-text-input
                        :label="$isEdit ? 'New Password' : 'Password'"
                        name="password"
                        type="password"
                        :required="!$isEdit"
                        :hint="$isEdit ? 'Leave blank to keep current' : ''"
                    />

                    <x-text-input
                        label="Confirm Password"
                        name="password_confirmation"
                        type="password"
                        :required="!$isEdit"
                    />
                </div>
            </x-form-section>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-sm transition-colors">
                    {{ $isEdit ? 'Save Changes' : 'Create User' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
