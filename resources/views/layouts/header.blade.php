<header class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
        <!-- Left side -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Desktop sidebar toggle -->
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="hidden lg:flex p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                <svg x-show="!sidebarCollapsed" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
                <svg x-show="sidebarCollapsed" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Breadcrumb -->
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-slate-500">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        </a>
                    </li>
                    @stack('breadcrumbs')
                </ol>
            </nav>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Search Bar -->
            <div class="hidden md:block" x-data="{ searchOpen: false }">
                <div class="relative">
                    <button @click="searchOpen = !searchOpen"
                            class="p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <div x-show="searchOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-slate-200 p-4"
                         @click.away="searchOpen = false"
                         style="display: none;">
                        <input type="text"
                               placeholder="Rechercher un investisseur..."
                               class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="relative" x-data="{ notificationsOpen: false }">
                <button @click="notificationsOpen = !notificationsOpen"
                        class="p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 relative">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <!-- Notification badge -->
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                </button>

                <div x-show="notificationsOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-slate-200"
                     @click.away="notificationsOpen = false"
                     style="display: none;">
                    <div class="p-4 border-b border-slate-200">
                        <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <div class="p-4 hover:bg-slate-50 border-b border-slate-100">
                            <p class="text-sm text-slate-600">Nouvel investisseur ajouté: Jean Dupont</p>
                            <p class="text-xs text-slate-400 mt-1">Il y a 2 heures</p>
                        </div>
                        <div class="p-4 hover:bg-slate-50 border-b border-slate-100">
                            <p class="text-sm text-slate-600">Email reçu de Sarah Mitchell</p>
                            <p class="text-xs text-slate-400 mt-1">Il y a 4 heures</p>
                        </div>
                        <div class="p-4 text-center">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User dropdown -->
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center space-x-3 p-2 rounded-md hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <img class="h-8 w-8 rounded-full object-cover"
                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nom_complet) }}&color=2563eb&background=e0e7ff"
                         alt="{{ auth()->user()->nom_complet }}">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-slate-700">{{ auth()->user()->nom_complet }}</p>
                        <p class="text-xs text-slate-500">
                            @if(auth()->user()->hasRole('Administrateur'))
                                Administrateur
                            @elseif(auth()->user()->hasRole('Éditeur'))
                                Éditeur
                            @else
                                Lecture seule
                            @endif
                        </p>
                    </div>
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="userMenuOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-slate-200 py-1"
                     @click.away="userMenuOpen = false"
                     style="display: none;">

                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="text-sm font-medium text-slate-900">{{ auth()->user()->nom_complet }}</p>
                        <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        <svg class="mr-3 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Mon profil
                    </a>

                    <a href="#" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        <svg class="mr-3 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Paramètres
                    </a>

                    <div class="border-t border-slate-100 mt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="mr-3 h-4 w-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
