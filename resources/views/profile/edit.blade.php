<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Profile Settings') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage your account settings and preferences.
            </p>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-6 text-center">
                <!-- Avatar -->
                <div class="relative inline-block mb-4">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <button class="absolute bottom-0 right-1/2 translate-x-1/2 translate-y-1/4 w-8 h-8 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'User' }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="badge-primary">Free Plan</span>
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">48</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Applications</p>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">5</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Interviews</p>
                    </div>
                </div>

                <!-- Member Since -->
                <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                    Member since {{ Auth::user()->created_at?->format('M Y') ?? 'Dec 2024' }}
                </div>
            </div>

            <!-- Navigation -->
            <div class="card mt-6 p-2">
                <nav class="space-y-1">
                    <a href="#profile-info" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile Information
                    </a>
                    <a href="#password" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Password & Security
                    </a>
                    <a href="#delete-account" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Account
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Profile Information -->
            <div id="profile-info" class="card p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Update your account's profile information and email address.
                    </p>
                </div>

                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Update Password -->
            <div id="password" class="card p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Password</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                </div>

                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete Account -->
            <div id="delete-account" class="card p-6 border-danger-200 dark:border-danger-900/50">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-danger-600 dark:text-danger-400">Delete Account</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                    </p>
                </div>

                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
