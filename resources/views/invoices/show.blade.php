<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('invoices.index') }}" class="text-gray-500 hover:text-gray-700 mr-2">
                    &larr; Back
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Invoice {{ $invoice->invoice_number }}
                </h2>
            </div>
            <div>
                @if($invoice->status !== 'paid')
                    <a href="{{ route('payments.create', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Record Payment
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Left Column: Invoice Details & Items -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Student Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Billed To</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            @if($invoice->student->photo_path)
                                <img src="{{ Storage::url($invoice->student->photo_path) }}" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xl font-bold">
                                    {{ substr($invoice->student->first_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-lg">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                            <p class="text-gray-600 text-sm">ID: {{ $invoice->student->student_number }}</p>
                            <p class="text-gray-600 text-sm">Programme: {{ $invoice->student->programme->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Invoice Items</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b text-gray-500 text-sm">
                                <th class="text-left pb-2">Description</th>
                                <th class="text-right pb-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="py-3 text-sm">{{ $item->due->category_name }}</td>
                                    <td class="py-3 text-right text-sm">GHS {{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-bold text-lg">
                                <td class="py-4 text-right pr-4">Total Amount:</td>
                                <td class="py-4 text-right">GHS {{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Right Column: Summary & Payments -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Invoice Summary</h3>
                    
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Status</span>
                        @if($invoice->status === 'paid')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        @elseif($invoice->status === 'partial')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Partial</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unpaid</span>
                        @endif
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Academic Session</span>
                        <span class="font-medium">{{ $invoice->academicSession->name }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Due Date</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                    </div>
                    <hr class="my-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Total Billed</span>
                        <span class="font-medium">GHS {{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Total Paid</span>
                        <span class="font-medium text-green-600">GHS {{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-4">
                        <span>Balance Due</span>
                        <span class="{{ $invoice->balance > 0 ? 'text-red-600' : 'text-green-600' }}">GHS {{ number_format($invoice->balance, 2) }}</span>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Payment History</h3>
                    @if($invoice->payments->count() > 0)
                        <ul class="space-y-3">
                            @foreach($invoice->payments as $payment)
                                <li class="border-b pb-2 last:border-0 last:pb-0">
                                    <div class="flex justify-between text-sm">
                                        <span class="font-semibold text-green-600">+ GHS {{ number_format($payment->amount, 2) }}</span>
                                        <span class="text-gray-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>{{ ucfirst($payment->payment_method) }}</span>
                                        <span>Ref: {{ $payment->reference_number }}</span>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <a href="{{ route('receipts.download', $payment) }}" class="text-xs text-indigo-600 hover:text-indigo-900" target="_blank">Download Receipt</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic text-center py-4">No payments recorded yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
