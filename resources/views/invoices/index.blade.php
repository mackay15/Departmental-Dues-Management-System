<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoices') }}
            </h2>
            @role('HOD')
            <a href="{{ route('invoices.generate') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Generate Invoices
            </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                            {{ session('info') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('invoices.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Invoice # or Student..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex">
                            <select name="status" class="rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 flex-1 border-r-0">
                                <option value="">All Statuses</option>
                                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Filter</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                            <a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}
                                            <div class="text-xs text-gray-500">{{ $invoice->student->index_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $invoice->academicSession->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            GHS {{ number_format($invoice->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            GHS {{ number_format($invoice->balance, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($invoice->status === 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                            @elseif($invoice->status === 'partial')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Partial</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unpaid</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            @if(auth()->user()->hasRole('Student') && $invoice->status !== 'paid')
                                                <a href="{{ route('student.payments.pay', $invoice) }}" class="text-emerald-650 hover:text-emerald-800 font-bold">Pay Now</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
