<!-- Top Navbar -->
<nav 
    class="navbar fixed top-0 right-0 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-all duration-300"
    :class="sidebarOpen ? 'lg:left-64' : 'lg:left-20'"
>
    <!-- Left Side -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Button -->
        <button 
            @click="sidebarMobileOpen = true"
            class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Breadcrumb / Page Title -->
        <div class="hidden sm:block">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>
    </div>

    <!-- Right Side -->
    <div class="flex items-center gap-3">
        <!-- Search Button -->
        <button class="flex items-center justify-center w-10 h-10 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>

        <!-- Notifications -->
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="relative flex items-center justify-center w-10 h-10 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <!-- Notification Badge -->
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-danger-500 rounded-full"></span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div 
                x-show="open" 
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="dropdown-menu w-80 right-0"
            >
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</p>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <a href="#" class="dropdown-item py-3">
                        <div class="w-10 h-10 bg-success-100 dark:bg-success-900/50 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Application viewed</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Google viewed your application</p>
                        </div>
                    </a>
                    <a href="#" class="dropdown-item py-3">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/50 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">New job match</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">5 new jobs match your profile</p>
                        </div>
                    </a>
                </div>
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">View all notifications</a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-gray-700"></div>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button 
                @click="open = !open"
                class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            >
                <div class="w-9 h-9 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-xl flex items-center justify-center text-white font-semibold text-sm">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-700 dark:text-white">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Free Plan</p>
                </div>
                <svg class="hidden md:block w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Profile Dropdown Menu -->
            <div 
                x-show="open" 
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="dropdown-menu"
            >
                <!-- User Info -->
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>
                
                <!-- Menu Items -->
                <div class="py-2">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Settings</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Billing</span>
                    </a>
                </div>
                
                <!-- Logout -->
                <div class="border-t border-gray-100 dark:border-gray-800 py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item-danger w-full text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Sign out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
