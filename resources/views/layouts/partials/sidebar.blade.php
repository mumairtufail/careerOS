<!-- Sidebar -->
<aside 
    class="fixed left-0 top-0 h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 border-r border-gray-800 z-40 transition-all duration-300 ease-in-out"
    :class="{
        'w-64': sidebarOpen,
        'w-20': !sidebarOpen,
        '-translate-x-full lg:translate-x-0': !sidebarMobileOpen,
        'translate-x-0': sidebarMobileOpen
    }"
>
    <!-- Logo Section -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span 
                class="text-xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent transition-opacity duration-300"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
            >
                CareerOS
            </span>
        </a>
        
        <!-- Collapse Button (Desktop) -->
        <button 
            @click="sidebarOpen = !sidebarOpen"
            class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-all duration-200"
            :class="!sidebarOpen ? 'absolute left-1/2 -translate-x-1/2' : ''"
        >
            <svg class="w-5 h-5 transition-transform duration-300" :class="!sidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
        
        <!-- Close Button (Mobile) -->
        <button 
            @click="sidebarMobileOpen = false"
            class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-6 no-scrollbar">
        <!-- Main Menu -->
        <div class="px-3 mb-6">
            <p 
                class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 transition-opacity duration-300"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
            >
                Main Menu
            </p>
            
            <div class="space-y-1">
                <!-- Dashboard -->
                <a 
                    href="{{ route('dashboard') }}" 
                    class="sidebar-link group {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-primary-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Dashboard
                    </span>
                </a>

                <!-- Jobs -->
                <a 
                    href="#" 
                    class="sidebar-link group {{ request()->routeIs('jobs.*') ? 'sidebar-link-active' : '' }}"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('jobs.*') ? 'text-primary-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Jobs
                    </span>
                </a>

                <!-- Applications -->
                <a 
                    href="#" 
                    class="sidebar-link group {{ request()->routeIs('applications.*') ? 'sidebar-link-active' : '' }}"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('applications.*') ? 'text-primary-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Applications
                    </span>
                    <!-- Badge -->
                    <span 
                        class="ml-auto bg-primary-500 text-white text-xs font-medium px-2 py-0.5 rounded-full transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        12
                    </span>
                </a>

                <!-- Companies -->
                <a 
                    href="#" 
                    class="sidebar-link group {{ request()->routeIs('companies.*') ? 'sidebar-link-active' : '' }}"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('companies.*') ? 'text-primary-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Companies
                    </span>
                </a>
            </div>
        </div>

        <!-- Tools -->
        <div class="px-3 mb-6">
            <p 
                class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 transition-opacity duration-300"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
            >
                Tools
            </p>
            
            <div class="space-y-1">
                <!-- Resume Builder -->
                <a 
                    href="#" 
                    class="sidebar-link group"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Resume Builder
                    </span>
                </a>

                <!-- Cover Letters -->
                <a 
                    href="#" 
                    class="sidebar-link group"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Cover Letters
                    </span>
                </a>

                <!-- Analytics -->
                <a 
                    href="#" 
                    class="sidebar-link group"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Analytics
                    </span>
                </a>
            </div>
        </div>

        <!-- Settings -->
        <div class="px-3">
            <p 
                class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 transition-opacity duration-300"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
            >
                Settings
            </p>
            
            <div class="space-y-1">
                <!-- Settings -->
                <a 
                    href="#" 
                    class="sidebar-link group"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Settings
                    </span>
                </a>

                <!-- Help -->
                <a 
                    href="#" 
                    class="sidebar-link group"
                    :class="!sidebarOpen ? 'justify-center px-2' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span 
                        class="transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
                    >
                        Help Center
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <!-- User Section at Bottom -->
    <div class="border-t border-gray-800 p-4">
        <a 
            href="{{ route('profile.edit') }}" 
            class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-800 transition-all duration-200"
            :class="!sidebarOpen ? 'justify-center' : ''"
        >
            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-secondary-400 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div 
                class="flex-1 min-w-0 transition-opacity duration-300"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0 hidden'"
            >
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
            </div>
        </a>
    </div>
</aside>
