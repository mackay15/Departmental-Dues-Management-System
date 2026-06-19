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
                    {{ __('Debtors Report') }}
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
            
            <!-- Filter Bar Card -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-6 mb-8">
                <form action="{{ route('reports.debtors') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-6">
                    <div class="flex-1">
                        <x-input-label for="programme_id" :value="__('Filter by Programme')" class="text-azure-700 font-semibold" />
                        <select name="programme_id" id="programme_id" class="mt-1 block w-full rounded-xl border-azure-200 focus:border-azure-400 focus:ring focus:ring-azure-250 focus:ring-opacity-50 text-sm text-azure-900 bg-azure-50/20">
                            <option value="">All Programmes</option>
                            @foreach($programmes as $prog)
                                <option value="{{ $prog->id }}" {{ request('programme_id') == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->name }} ({{ $prog->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" class="inline-flex justify-center items-center px-5 py-2.5 bg-azure-800 hover:bg-azure-700 text-white font-bold text-sm rounded-xl transition-all duration-150 shadow-md shadow-azure-800/10">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                        @if(request()->filled('programme_id'))
                            <a href="{{ route('reports.debtors') }}" class="inline-flex justify-center items-center px-5 py-2.5 bg-azure-100 hover:bg-azure-200 text-azure-800 font-bold text-sm rounded-xl transition-all duration-150">
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
                        <h3 class="text-lg font-bold text-azure-950">Debtors List</h3>
                        <p class="text-sm text-azure-500 mt-0.5">Students who currently owe outstanding dues.</p>
                    </div>
                    <div class="bg-rose-50 border border-rose-100 px-4 py-2 rounded-xl text-center">
                        <div class="text-xs text-rose-600 font-semibold uppercase tracking-wider">Total Unpaid Balance</div>
                        <div class="text-lg font-extrabold text-rose-700 mt-0.5">GHS {{ number_format($debtors->sum('balance'), 2) }}</div>
                    </div>
                </div>

                @if($debtors->isEmpty())
                    <div class="text-center py-12">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-azure-900">Zero Debtors Found</h4>
                        <p class="text-sm text-azure-500 mt-1">Excellent! There are no unpaid invoices matching the current filter parameters.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-azure-100">
                            <thead class="bg-azure-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Student Number</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Student Name</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Programme</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Academic Session</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-azure-800 uppercase tracking-wider">Invoice Total</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-azure-800 uppercase tracking-wider">Paid Amount</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-azure-800 uppercase tracking-wider">Outstanding Balance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-azure-50">
                                @foreach($debtors as $invoice)
                                    <tr class="hover:bg-azure-50/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-azure-950">
                                            {{ $invoice->student->student_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-900 font-medium">
                                            {{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-700">
                                            {{ $invoice->student->programme->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-700">
                                            <span class="bg-azure-50 border border-azure-100 text-azure-800 px-2 py-0.5 rounded text-xs">
                                                {{ $invoice->academicSession->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-azure-950 font-medium">
                                            GHS {{ number_format($invoice->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-emerald-600 font-medium">
                                            GHS {{ number_format($invoice->paid_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-rose-600 font-bold">
                                            GHS {{ number_format($invoice->balance, 2) }}
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
