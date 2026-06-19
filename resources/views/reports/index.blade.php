<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
            {{ __('Financial Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 bg-white p-6 rounded-2xl border border-azure-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-azure-800 to-azure-400"></div>
                <h3 class="text-lg font-bold text-azure-950">Overview</h3>
                <p class="text-sm text-azure-600 mt-1">Select one of the system reports below to view, filter, and export financial and academic records as CSV.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Debtors Report Card -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl border border-azure-100 transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-rose-50 text-rose-600 rounded-2xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-azure-950">Debtors Statement</h3>
                                <p class="text-xs text-azure-500 font-semibold uppercase mt-0.5 tracking-wider">Outstanding Balances</p>
                            </div>
                        </div>
                        <p class="text-sm text-azure-700 leading-relaxed">
                            Retrieve a detailed breakdown of all students with outstanding balances. You can filter the records by specific programs and export the entire list to CSV for external administrative tasks.
                        </p>
                    </div>
                    <div class="px-6 py-4 bg-azure-50/40 border-t border-azure-50 flex justify-end">
                        <a href="{{ route('reports.debtors') }}" class="inline-flex items-center text-sm font-bold text-azure-800 hover:text-azure-900 transition-colors duration-150">
                            Run Report
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Revenue Report Card -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl border border-azure-100 transition-all duration-300 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1m0-1H8m4 0h4m-4-8H8" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-azure-950">Revenue Summary</h3>
                                <p class="text-xs text-azure-500 font-semibold uppercase mt-0.5 tracking-wider">Completed Payments</p>
                            </div>
                        </div>
                        <p class="text-sm text-azure-700 leading-relaxed">
                            Track the inflow of student payments across specified date ranges. Generate summaries of all completed payments to monitor revenue collection progress and reconcile with financial deposits.
                        </p>
                    </div>
                    <div class="px-6 py-4 bg-azure-50/40 border-t border-azure-50 flex justify-end">
                        <a href="{{ route('reports.revenue') }}" class="inline-flex items-center text-sm font-bold text-azure-800 hover:text-azure-900 transition-colors duration-150">
                            Run Report
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
