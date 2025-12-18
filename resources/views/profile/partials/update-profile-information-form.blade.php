<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label">{{ __('Full Name') }}</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="input"
                value="{{ old('name', $user->name) }}"
                required 
                autofocus 
                autocomplete="name"
            >
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="input"
                value="{{ old('email', $user->email) }}"
                required 
                autocomplete="username"
            >
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-warning-800 dark:text-warning-200">
                                {{ __('Your email address is unverified.') }}
                            </p>
                            <button form="send-verification" class="text-sm font-medium text-warning-600 hover:text-warning-700 dark:text-warning-400 dark:hover:text-warning-300 underline">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 text-sm font-medium text-success-600 dark:text-success-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success-600 dark:text-success-400"
                >{{ __('Profile updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
