<nav x-data="{ open: false }" class="bg-azure-800 border-b border-azure-900 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" class="h-9 w-9 rounded-full object-cover">
                        <span class="text-white font-bold text-lg tracking-tight">COMPSSA SFMS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('students.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Students
                    </a>
                    <a href="{{ route('programmes.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('programmes.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Programmes
                    </a>
                    <a href="{{ route('academic-levels.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('academic-levels.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Levels
                    </a>
                    <a href="{{ route('academic-sessions.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('academic-sessions.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Sessions
                    </a>
                    <a href="{{ route('dues.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('dues.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Dues
                    </a>
                    <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Invoices
                    </a>
                    <a href="{{ route('payments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Payments
                    </a>
                    <a href="{{ route('promotions.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('promotions.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Promotions
                    </a>
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">
                        Reports
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-azure-600 text-sm leading-4 font-medium rounded-md text-azure-100 bg-azure-700 hover:bg-azure-600 focus:outline-none transition ease-in-out duration-200">
                            <div class="flex items-center">
                                <div class="h-7 w-7 rounded-full bg-azure-100 text-azure-800 flex items-center justify-center text-xs font-bold mr-2">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                {{ Auth::user()->name }}
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-azure-200 hover:text-white hover:bg-azure-700 focus:outline-none focus:bg-azure-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Dashboard</a>
            <a href="{{ route('students.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('students.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Students</a>
            <a href="{{ route('programmes.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('programmes.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Programmes</a>
            <a href="{{ route('academic-levels.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('academic-levels.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Levels</a>
            <a href="{{ route('academic-sessions.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('academic-sessions.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Sessions</a>
            <a href="{{ route('dues.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('dues.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Dues</a>
            <a href="{{ route('invoices.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('invoices.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Invoices</a>
            <a href="{{ route('payments.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Payments</a>
            <a href="{{ route('promotions.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('promotions.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Promotions</a>
            <a href="{{ route('reports.index') }}" class="block px-3 py-2 text-base font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-azure-700 text-white' : 'text-azure-100 hover:bg-azure-700 hover:text-white' }}">Reports</a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-azure-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-azure-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-base font-medium text-azure-100 rounded-md hover:bg-azure-700 hover:text-white">Profile</a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="block px-3 py-2 text-base font-medium text-azure-100 rounded-md hover:bg-azure-700 hover:text-white"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
