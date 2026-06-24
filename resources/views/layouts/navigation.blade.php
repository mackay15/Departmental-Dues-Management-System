<div x-data="{ mobileMenuOpen: false }" class="relative shrink-0">
    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex flex-col w-64 h-screen sticky top-0 bg-azure-900 text-azure-100 border-r border-azure-950 shadow-xl overflow-y-hidden">
        <!-- Logo / Brand Header -->
        <div class="h-16 flex items-center px-6 bg-azure-950 border-b border-azure-950 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" class="h-9 w-9 rounded-full object-cover ring-2 ring-azure-400/50 group-hover:scale-105 transition-all duration-200">
                <span class="text-white font-bold text-lg tracking-tight group-hover:text-azure-200 transition-colors">COMPSSA SFMS</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('dashboard') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Students -->
            @hasanyrole('HOD|Finance Officer')
            <a href="{{ route('students.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('students.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('students.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                <span>Students</span>
            </a>
            @endhasanyrole

            <!-- Sessions -->
            @role('HOD')
            <a href="{{ route('academic-sessions.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('academic-sessions.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('academic-sessions.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Sessions</span>
            </a>
            @endrole

            <!-- Dues -->
            @hasanyrole('HOD|Finance Officer')
            <a href="{{ route('dues.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('dues.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('dues.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Dues</span>
            </a>
            @endhasanyrole

            <!-- Invoices -->
            @hasanyrole('HOD|Finance Officer|Student')
            <a href="{{ route('invoices.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('invoices.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('invoices.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Invoices</span>
            </a>
            @endhasanyrole

            <!-- Payments -->
            @hasanyrole('HOD|Finance Officer|Auditor|Student')
            <a href="{{ route('payments.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('payments.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('payments.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Payments</span>
            </a>
            @endhasanyrole

            <!-- Promotions -->
            @role('HOD')
            <a href="{{ route('promotions.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('promotions.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('promotions.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <span>Promotions</span>
            </a>
            @endrole

            <!-- Reports -->
            @hasanyrole('HOD|Auditor')
            <a href="{{ route('reports.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('reports.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Reports</span>
            </a>
            @endhasanyrole

            <!-- Users -->
            @role('HOD')
            <a href="{{ route('users.index') }}" 
               class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('users.*') ? 'text-azure-400' : 'text-azure-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Users</span>
            </a>
            @endrole
        </nav>

        <!-- User Settings Dropdown Card at Bottom -->
        <div class="p-4 border-t border-azure-950 bg-azure-950/40 shrink-0" x-data="{ userMenuOpen: false }">
            <div class="relative">
                <button @click="userMenuOpen = !userMenuOpen" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-azure-800/40 transition-all text-left">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="h-9 w-9 rounded-full bg-azure-700 border border-azure-600 flex items-center justify-center text-sm font-bold text-white shrink-0 shadow-inner">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-azure-300 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <svg class="h-4 w-4 text-azure-300 shrink-0 transform transition-transform duration-200" :class="{'rotate-180': userMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Profile/Logout Menu -->
                <div x-show="userMenuOpen"
                     @click.away="userMenuOpen = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     class="absolute bottom-full left-0 right-0 mb-2 bg-azure-950 border border-azure-800 rounded-lg shadow-xl overflow-hidden z-30"
                     style="display: none;">
                    
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-azure-200 hover:bg-azure-800 hover:text-white transition-colors">
                        {{ __('Profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="border-t border-azure-800">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2.5 text-sm text-rose-300 hover:bg-rose-950/30 hover:text-rose-100 transition-colors">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Top Header Bar -->
    <div class="md:hidden flex items-center justify-between h-16 px-4 bg-azure-900 border-b border-azure-950 text-white shadow-md">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" class="h-8 w-8 rounded-full object-cover">
            <span class="font-bold text-base tracking-tight text-white">COMPSSA SFMS</span>
        </a>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-md hover:bg-azure-800 text-azure-100 focus:outline-none transition-colors">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation Drawer Overlay/Slide-in -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 md:hidden flex" 
         style="display: none;">
        
        <!-- Backdrop click-away -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

        <!-- Drawer Content -->
        <aside x-show="mobileMenuOpen"
               x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="relative flex flex-col w-72 max-w-[85vw] h-full bg-azure-900 text-azure-100 shadow-2xl overflow-y-hidden">
            
            <!-- Close header in drawer -->
            <div class="h-16 flex items-center justify-between px-5 bg-azure-950 border-b border-azure-950 shrink-0">
                <div class="flex items-center space-x-2.5">
                    <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" class="h-8 w-8 rounded-full object-cover ring-2 ring-azure-400/50">
                    <span class="text-white font-bold text-base tracking-tight">COMPSSA SFMS</span>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 rounded-md hover:bg-azure-800 text-azure-200 hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Drawer Links -->
            <nav class="flex-grow px-3 py-6 space-y-1.5 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('dashboard') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Students -->
                @hasanyrole('HOD|Finance Officer')
                <a href="{{ route('students.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('students.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('students.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                    <span>Students</span>
                </a>
                @endhasanyrole

                <!-- Sessions -->
                @role('HOD')
                <a href="{{ route('academic-sessions.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('academic-sessions.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('academic-sessions.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Sessions</span>
                </a>
                @endrole

                <!-- Dues -->
                @hasanyrole('HOD|Finance Officer')
                <a href="{{ route('dues.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('dues.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('dues.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Dues</span>
                </a>
                @endhasanyrole

                <!-- Invoices -->
                @hasanyrole('HOD|Finance Officer|Student')
                <a href="{{ route('invoices.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('invoices.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('invoices.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Invoices</span>
                </a>
                @endhasanyrole

                <!-- Payments -->
                @hasanyrole('HOD|Finance Officer|Auditor|Student')
                <a href="{{ route('payments.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('payments.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('payments.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>Payments</span>
                </a>
                @endhasanyrole

                <!-- Promotions -->
                @role('HOD')
                <a href="{{ route('promotions.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('promotions.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('promotions.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Promotions</span>
                </a>
                @endrole

                <!-- Reports -->
                @hasanyrole('HOD|Auditor')
                <a href="{{ route('reports.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('reports.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Reports</span>
                </a>
                @endhasanyrole

                <!-- Users -->
                @role('HOD')
                <a href="{{ route('users.index') }}" 
                   class="group flex items-center py-2.5 px-3.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-azure-800 text-white shadow-sm border-l-4 border-azure-400' : 'text-azure-200 hover:bg-azure-800/40 hover:text-white border-l-4 border-transparent' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('users.*') ? 'text-azure-400' : 'text-azure-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Users</span>
                </a>
                @endrole
            </nav>

            <!-- User Settings Dropdown Card at Bottom inside Drawer -->
            <div class="p-4 border-t border-azure-950 bg-azure-950/40 shrink-0" x-data="{ userMenuOpenMobile: false }">
                <div class="relative">
                    <button @click="userMenuOpenMobile = !userMenuOpenMobile" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-azure-800/40 transition-all text-left">
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="h-9 w-9 rounded-full bg-azure-700 border border-azure-600 flex items-center justify-center text-sm font-bold text-white shrink-0 shadow-inner">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-azure-300 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <svg class="h-4 w-4 text-azure-300 shrink-0 transform transition-transform duration-200" :class="{'rotate-180': userMenuOpenMobile}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Profile/Logout Menu -->
                    <div x-show="userMenuOpenMobile"
                         @click.away="userMenuOpenMobile = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute bottom-full left-0 right-0 mb-2 bg-azure-950 border border-azure-800 rounded-lg shadow-xl overflow-hidden z-30"
                         style="display: none;">
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-azure-200 hover:bg-azure-800 hover:text-white transition-colors">
                            {{ __('Profile') }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="border-t border-azure-800">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2.5 text-sm text-rose-300 hover:bg-rose-950/30 hover:text-rose-100 transition-colors">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<style>
    /* Premium custom scrollbar styling for the vertical sidebar navigation */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
