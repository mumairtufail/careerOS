<x-guest-layout>
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-warning-500 to-warning-600 rounded-2xl flex items-center justify-center shadow-lg shadow-warning-500/30">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Verify your email</h2>
        <p class="text-gray-400">Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-success-600 dark:text-success-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-medium text-success-800 dark:text-success-200">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full py-3.5 text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Resend verification email</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-ghost w-full py-3">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Log out</span>
            </button>
        </form>
    </div>
</x-guest-layout>
