<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-500 hover:text-gray-700 mr-2">
                    &larr; Back to Invoice
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Record Payment for Invoice: {{ $invoice->invoice_number }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Invoice Summary Box -->
                    <div class="mb-6 bg-gray-50 p-4 border border-gray-200 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Billed To</p>
                            <p class="font-bold text-lg">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                            <p class="text-sm text-gray-500">{{ $invoice->student->student_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Balance Due</p>
                            <p class="font-bold text-2xl text-red-600">GHS {{ number_format($invoice->balance, 2) }}</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payments.store', $invoice) }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Amount -->
                            <div>
                                <x-input-label for="amount" :value="__('Amount to Pay (GHS)')" />
                                <x-text-input id="amount" class="block mt-1 w-full text-lg font-bold" type="number" step="0.01" max="{{ $invoice->balance }}" name="amount" value="{{ old('amount', $invoice->balance) }}" required autofocus />
                                <span class="text-xs text-gray-500">Defaults to the full balance. Max: {{ $invoice->balance }}</span>
                            </div>

                            <!-- Payment Date -->
                            <div>
                                <x-input-label for="payment_date" :value="__('Payment Date')" />
                                <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required />
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <x-input-label for="payment_method" :value="__('Payment Method')" />
                                <select id="payment_method" name="payment_method" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="momo" {{ old('payment_method') == 'momo' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Reference Number -->
                            <div>
                                <x-input-label for="reference_number" :value="__('Reference Number (Optional)')" />
                                <x-text-input id="reference_number" class="block mt-1 w-full" type="text" name="reference_number" value="{{ old('reference_number') }}" placeholder="e.g. MOMO Txn ID" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2">{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-600 mr-4">Cancel</a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-900" onclick="return confirm('Are you sure you want to record this payment?')">
                                {{ __('Record Payment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
