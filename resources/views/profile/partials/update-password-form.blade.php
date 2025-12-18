<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="input"
                autocomplete="current-password"
            >
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                <input 
                    id="update_password_password" 
                    name="password" 
                    type="password" 
                    class="input"
                    autocomplete="new-password"
                >
                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Minimum 8 characters</p>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="input"
                    autocomplete="new-password"
                >
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success-600 dark:text-success-400"
                >{{ __('Password updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
