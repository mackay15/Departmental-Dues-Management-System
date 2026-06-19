<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl p-6 mb-6 border border-azure-100 relative">
                <!-- Branding Accent -->
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-azure-800 to-azure-400"></div>
                <h3 class="text-xl font-bold text-azure-950 mt-1">Welcome, {{ $student->first_name ?? 'Student' }}!</h3>
                <p class="text-sm text-azure-600 mt-1.5 font-medium flex flex-wrap gap-2 items-center">
                    <span class="bg-azure-100 text-azure-800 px-3 py-1 rounded-full text-xs">Programme: {{ $student->programme->name ?? 'N/A' }}</span>
                    <span class="text-azure-300">|</span>
                    <span class="bg-azure-50 text-azure-700 px-3 py-1 rounded-full text-xs border border-azure-100">Level: {{ $student->currentLevel->name ?? 'N/A' }}</span>
                </p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Outstanding Dues -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl p-6 border-l-4 {{ $totalOutstanding > 0 ? 'border-rose-500' : 'border-emerald-500' }} transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-azure-600 text-xs font-semibold uppercase tracking-wider">Total Outstanding Balance</div>
                            <div class="mt-2 text-3xl font-extrabold {{ $totalOutstanding > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                GHS {{ number_format($totalOutstanding, 2) }}
                            </div>
                        </div>
                        <div class="p-3 {{ $totalOutstanding > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white overflow-hidden shadow-md hover:shadow-lg sm:rounded-2xl p-6 border-l-4 border-azure-800 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-azure-600 text-xs font-semibold uppercase tracking-wider">Financial Status</div>
                            <div class="mt-2 text-2xl font-extrabold text-azure-850">
                                {{ $totalOutstanding > 0 ? 'Action Required' : 'Cleared' }}
                            </div>
                        </div>
                        <div class="p-3 bg-azure-100/60 rounded-xl text-azure-800">
                            @if($totalOutstanding > 0)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Invoices -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl p-6 border border-azure-100">
                    <div class="flex items-center justify-between mb-4 border-b border-azure-50 pb-4">
                        <h3 class="text-lg font-bold text-azure-900">Recent Invoices</h3>
                        <svg class="w-5 h-5 text-azure-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($recentInvoices as $invoice)
                            <div class="flex justify-between items-center border-b border-azure-50 pb-3 hover:bg-azure-50/20 px-2 rounded-lg transition-colors duration-150">
                                <div>
                                    <p class="text-sm font-semibold text-azure-950">{{ $invoice->academicSession->name ?? 'Session' }} Dues</p>
                                    <p class="text-xs text-azure-500 mt-1">Status: 
                                        <span class="uppercase font-bold px-2 py-0.5 rounded text-[10px] {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">{{ $invoice->status }}</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-azure-950">GHS {{ number_format($invoice->balance, 2) }}</p>
                                    <p class="text-xs text-azure-400 mt-0.5">Balance</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-azure-500 italic">No recent invoices.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl p-6 border border-azure-100">
                    <div class="flex items-center justify-between mb-4 border-b border-azure-50 pb-4">
                        <h3 class="text-lg font-bold text-azure-900">Payment History</h3>
                        <svg class="w-5 h-5 text-azure-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1m0-1H8m4 0h4m-4-8H8" />
                        </svg>
                    </div>
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                        @forelse($recentPayments as $payment)
                            <div class="flex justify-between items-center border-b border-azure-50 pb-3 hover:bg-azure-50/20 px-2 rounded-lg transition-colors duration-150">
                                <div>
                                    <p class="text-sm font-semibold text-azure-950">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                    <p class="text-xs text-azure-500 mt-1">Receipt: <span class="font-mono text-azure-700 bg-azure-50 px-1.5 py-0.5 rounded text-[10px] border border-azure-100">{{ $payment->receipt_number ?? 'N/A' }}</span></p>
                                </div>
                                <div class="text-sm font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                                    GHS {{ number_format($payment->amount, 2) }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-sm text-azure-500 italic">No payments recorded yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
