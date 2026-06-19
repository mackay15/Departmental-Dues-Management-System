<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('reports.index') }}" class="p-2 bg-white border border-azure-200 text-azure-800 rounded-xl hover:bg-azure-50 transition-colors duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
                    {{ __('Revenue Report') }}
                </h2>
            </div>
            <div class="flex space-x-3">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all duration-150 shadow-md shadow-emerald-600/10">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    {{ __('Export CSV') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters Card -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-6 mb-8">
                <form action="{{ route('reports.revenue') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <x-input-label for="start_date" :value="__('Start Date')" class="text-azure-700 font-semibold" />
                        <x-text-input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-xl border-azure-200 focus:border-azure-400 focus:ring-azure-250 focus:ring-opacity-50 text-sm text-azure-900 bg-azure-50/20" />
                    </div>

                    <div>
                        <x-input-label for="end_date" :value="__('End Date')" class="text-azure-700 font-semibold" />
                        <x-text-input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-xl border-azure-200 focus:border-azure-400 focus:ring-azure-250 focus:ring-opacity-50 text-sm text-azure-900 bg-azure-50/20" />
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-5 py-2.5 bg-azure-800 hover:bg-azure-700 text-white font-bold text-sm rounded-xl transition-all duration-150 shadow-md shadow-azure-800/10">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Payments
                        </button>
                        @if(request()->filled('start_date') || request()->filled('end_date'))
                            <a href="{{ route('reports.revenue') }}" class="inline-flex justify-center items-center px-5 py-2.5 bg-azure-100 hover:bg-azure-200 text-azure-800 font-bold text-sm rounded-xl transition-all duration-150">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Report Table Card -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-6">
                <!-- Meta stats -->
                <div class="flex flex-wrap items-center justify-between border-b border-azure-50 pb-4 mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-azure-950">Revenue Summary</h3>
                        <p class="text-sm text-azure-500 mt-0.5">Logs of all completed student dues payments.</p>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100 px-4 py-2 rounded-xl text-center">
                        <div class="text-xs text-emerald-600 font-semibold uppercase tracking-wider">Total Revenue Collected</div>
                        <div class="text-lg font-extrabold text-emerald-700 mt-0.5">GHS {{ number_format($payments->sum('amount'), 2) }}</div>
                    </div>
                </div>

                @if($payments->isEmpty())
                    <div class="text-center py-12">
                        <div class="p-3 bg-azure-100/50 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3 text-azure-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1m0-1H8m4 0h4m-4-8H8" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-azure-900">No Payments Recorded</h4>
                        <p class="text-sm text-azure-500 mt-1">No completed payments exist for the selected date filters.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-azure-100">
                            <thead class="bg-azure-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Receipt No.</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Payment Date</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Student Number</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Student Name</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Academic Session</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-azure-800 uppercase tracking-wider">Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-azure-50">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-azure-50/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-azure-950 font-mono">
                                            {{ $payment->receipt_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-900">
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-700">
                                            {{ $payment->student->student_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-900 font-medium">
                                            {{ $payment->student->first_name ?? '' }} {{ $payment->student->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-750">
                                            <span class="bg-azure-100 text-azure-850 px-2 py-0.5 rounded text-xs font-semibold">
                                                {{ $payment->invoice->academicSession->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-emerald-600 font-bold">
                                            GHS {{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
