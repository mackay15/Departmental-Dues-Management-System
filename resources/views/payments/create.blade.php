<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('invoices.show', $invoice) }}" class="text-azure-600 hover:text-azure-800 transition-colors mr-3 font-semibold flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Invoice
            </a>
            <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
                Record Student Payment
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Invoice Summary Card -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-md border border-azure-100 p-6 sticky top-24">
                        <h3 class="text-lg font-extrabold text-azure-950 border-b border-azure-100 pb-3 mb-4">Invoice Summary</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Billed Student</p>
                                <p class="text-sm font-bold text-azure-950 mt-0.5">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                                <p class="text-xs font-semibold text-azure-600 font-mono mt-0.5">{{ $invoice->student->index_number }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Invoice Number</p>
                                <p class="text-sm font-bold text-azure-950 font-mono mt-0.5">{{ $invoice->invoice_number }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Academic Session</p>
                                <p class="text-sm font-bold text-azure-900 mt-0.5">{{ $invoice->academicSession->name }}</p>
                            </div>

                            <div class="border-t border-azure-50 pt-3 space-y-2">
                                <div class="flex justify-between text-xs text-azure-600">
                                    <span>Total Invoice Dues</span>
                                    <span class="font-semibold font-mono">GHS {{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-emerald-600">
                                    <span>Total Payments Posted</span>
                                    <span class="font-semibold font-mono">-GHS {{ number_format($invoice->paid_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-base font-extrabold text-azure-950 pt-1 border-t border-azure-50">
                                    <span>Outstanding Balance</span>
                                    <span class="font-mono text-rose-600">GHS {{ number_format($invoice->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Record Payment Details Form -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-md border border-azure-100 p-6 relative overflow-hidden" x-data="{ paymentMethod: '{{ old('payment_method', 'cash') }}' }">
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-azure-800 to-azure-400"></div>

                        <h3 class="text-xl font-extrabold text-azure-950 mt-1 mb-6">Enter Transaction Details</h3>

                        @if ($errors->any())
                            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl mb-6 text-sm">
                                <p class="font-bold mb-1">Please fix the following issues:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('payments.store', $invoice) }}" class="space-y-6">
                            @csrf

                            <!-- Payment Amount & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="amount" class="block text-sm font-bold text-azure-900 mb-1.5">Amount Paid (GHS)</label>
                                    <div class="relative rounded-xl shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-azure-400 font-bold text-sm">GHS</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="amount" 
                                               id="amount" 
                                               max="{{ $invoice->balance }}" 
                                               min="1" 
                                               value="{{ old('amount', $invoice->balance) }}" 
                                               class="pl-12 w-full rounded-xl border-azure-200 text-azure-950 font-bold text-lg focus:border-azure-500 focus:ring-azure-500 focus:ring-1" 
                                               required>
                                    </div>
                                    <p class="text-xs text-azure-500 mt-1">Defaults to remaining balance.</p>
                                </div>

                                <div>
                                    <label for="payment_date" class="block text-sm font-bold text-azure-900 mb-1.5">Payment Date</label>
                                    <input type="date" 
                                           name="payment_date" 
                                           id="payment_date" 
                                           value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                                           class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1" 
                                           required>
                                </div>
                            </div>

                            <!-- Payment Method Selection Tabs -->
                            <div>
                                <span class="block text-sm font-bold text-azure-900 mb-2.5">Payment Method</span>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <!-- Cash Tab -->
                                    <button type="button" 
                                            @click="paymentMethod = 'cash'"
                                            :class="paymentMethod === 'cash' ? 'border-azure-800 bg-azure-50/50 ring-2 ring-azure-800/10' : 'border-gray-200 hover:bg-gray-50/50'"
                                            class="flex flex-col items-center justify-center p-3.5 border rounded-xl transition-all duration-200 text-center group">
                                        <svg class="h-6 w-6 text-azure-700 mb-1.5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-xs font-bold text-azure-950">Cash</span>
                                    </button>

                                    <!-- MoMo Tab -->
                                    <button type="button" 
                                            @click="paymentMethod = 'momo'"
                                            :class="paymentMethod === 'momo' ? 'border-azure-800 bg-azure-50/50 ring-2 ring-azure-800/10' : 'border-gray-200 hover:bg-gray-50/50'"
                                            class="flex flex-col items-center justify-center p-3.5 border rounded-xl transition-all duration-200 text-center group">
                                        <svg class="h-6 w-6 text-azure-700 mb-1.5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs font-bold text-azure-950">Mobile Money</span>
                                    </button>

                                    <!-- Card Tab -->
                                    <button type="button" 
                                            @click="paymentMethod = 'card'"
                                            :class="paymentMethod === 'card' ? 'border-azure-800 bg-azure-50/50 ring-2 ring-azure-800/10' : 'border-gray-200 hover:bg-gray-50/50'"
                                            class="flex flex-col items-center justify-center p-3.5 border rounded-xl transition-all duration-200 text-center group">
                                        <svg class="h-6 w-6 text-azure-700 mb-1.5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="text-xs font-bold text-azure-950">Bank Card</span>
                                    </button>

                                    <!-- Bank Transfer Tab -->
                                    <button type="button" 
                                            @click="paymentMethod = 'bank_transfer'"
                                            :class="paymentMethod === 'bank_transfer' ? 'border-azure-800 bg-azure-50/50 ring-2 ring-azure-800/10' : 'border-gray-200 hover:bg-gray-50/50'"
                                            class="flex flex-col items-center justify-center p-3.5 border rounded-xl transition-all duration-200 text-center group">
                                        <svg class="h-6 w-6 text-azure-700 mb-1.5 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                        <span class="text-xs font-bold text-azure-950">Bank Direct</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Method Input -->
                            <input type="hidden" name="payment_method" :value="paymentMethod">

                            <!-- Mobile Money Form Details -->
                            <div x-show="paymentMethod === 'momo'" x-transition:enter="transition ease-out duration-150" class="space-y-4 bg-azure-50/20 p-4 border border-azure-100 rounded-2xl">
                                <div>
                                    <label class="block text-sm font-bold text-azure-900 mb-1.5">Momo Network Provider</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-white transition-colors bg-white/50">
                                            <input type="radio" name="momo_provider" value="mtn" class="text-azure-800 focus:ring-azure-800" {{ old('momo_provider', 'mtn') === 'mtn' ? 'checked' : '' }}>
                                            <span class="ml-2 text-xs font-bold text-azure-950 uppercase">MTN</span>
                                        </label>
                                        <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-white transition-colors bg-white/50">
                                            <input type="radio" name="momo_provider" value="telecel" class="text-azure-800 focus:ring-azure-800" {{ old('momo_provider') === 'telecel' ? 'checked' : '' }}>
                                            <span class="ml-2 text-xs font-bold text-azure-950 uppercase">Telecel</span>
                                        </label>
                                        <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-white transition-colors bg-white/50">
                                            <input type="radio" name="momo_provider" value="at" class="text-azure-800 focus:ring-azure-800" {{ old('momo_provider') === 'at' ? 'checked' : '' }}>
                                            <span class="ml-2 text-xs font-bold text-azure-950 uppercase">AT</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="momo_number" class="block text-sm font-bold text-azure-900 mb-1.5">Momo Phone Number</label>
                                    <input type="text" 
                                           name="momo_number" 
                                           id="momo_number" 
                                           value="{{ old('momo_number') }}"
                                           placeholder="e.g. 0541234567" 
                                           class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1" 
                                           maxlength="10"
                                           :required="paymentMethod === 'momo'">
                                </div>
                            </div>

                            <!-- Card Form Details -->
                            <div x-show="paymentMethod === 'card'" x-transition:enter="transition ease-out duration-150" class="space-y-4 bg-azure-50/20 p-4 border border-azure-100 rounded-2xl" style="display: none;">
                                <div>
                                    <label for="card_number" class="block text-sm font-bold text-azure-900 mb-1.5">Card Number</label>
                                    <input type="text" 
                                           name="card_number" 
                                           id="card_number" 
                                           value="{{ old('card_number') }}"
                                           placeholder="16-digit credit/debit card number" 
                                           class="w-full rounded-xl border-azure-200 text-azure-950 font-mono tracking-widest focus:border-azure-500 focus:ring-azure-500 focus:ring-1" 
                                           maxlength="16"
                                           :required="paymentMethod === 'card'">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-bold text-azure-900 mb-1.5">Expiry Date</label>
                                        <input type="text" 
                                               name="card_expiry" 
                                               id="card_expiry" 
                                               value="{{ old('card_expiry') }}"
                                               placeholder="MM/YY" 
                                               class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1 text-center" 
                                               maxlength="5"
                                               :required="paymentMethod === 'card'">
                                    </div>
                                    <div>
                                        <label for="card_cvv" class="block text-sm font-bold text-azure-900 mb-1.5">CVV</label>
                                        <input type="password" 
                                               name="card_cvv" 
                                               id="card_cvv" 
                                               placeholder="3 digits" 
                                               class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1 text-center" 
                                               maxlength="3"
                                               :required="paymentMethod === 'card'">
                                    </div>
                                </div>
                            </div>

                            <!-- Optional Reference Number (Visible for Cash/Bank Transfer, or can override generated one) -->
                            <div>
                                <label for="reference_number" class="block text-sm font-bold text-azure-900 mb-1.5">Transaction Reference (Optional)</label>
                                <input type="text" 
                                       name="reference_number" 
                                       id="reference_number" 
                                       value="{{ old('reference_number') }}" 
                                       placeholder="e.g. Bank slip ID, Cheque number, or MoMo Txn ID" 
                                       class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1">
                                <p class="text-xs text-azure-500 mt-1">If left blank, a unique reference number will be auto-generated by the system.</p>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-bold text-azure-900 mb-1.5">Payment Notes / Description (Optional)</label>
                                <textarea id="notes" 
                                          name="notes" 
                                          rows="2" 
                                          placeholder="Add any extra administrative comments about this transaction..." 
                                          class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Form Actions -->
                            <div class="pt-6 border-t border-azure-50 flex items-center justify-end">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-sm font-semibold text-azure-600 hover:text-azure-800 transition mr-6">Cancel</a>
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to post this payment and generate an official receipt?')"
                                        class="inline-flex items-center justify-center py-2.5 px-6 border border-transparent rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-md transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Post Payment & Issue Receipt
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
