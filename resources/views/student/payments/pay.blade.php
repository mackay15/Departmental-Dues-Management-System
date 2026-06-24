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
                Complete Payment
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Invoice Summary Card -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-md border border-azure-100 p-6 sticky top-24">
                        <h3 class="text-lg font-extrabold text-azure-950 border-b border-azure-100 pb-3 mb-4">Summary</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Invoice Number</p>
                                <p class="text-sm font-bold text-azure-950 font-mono mt-0.5">{{ $invoice->invoice_number }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Academic Session</p>
                                <p class="text-sm font-bold text-azure-900 mt-0.5">{{ $invoice->academicSession->name }}</p>
                            </div>

                            <div class="border-t border-azure-50 pt-3">
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Billed Items</p>
                                <ul class="mt-2 space-y-2">
                                    @foreach($invoice->items as $item)
                                        <div class="flex justify-between items-center text-xs text-azure-700">
                                            <span class="truncate pr-4">{{ $item->due->category_name }}</span>
                                            <span class="font-bold font-mono">GHS {{ number_format($item->amount, 2) }}</span>
                                        </div>
                                    @endforeach
                                </ul>
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
                                    <span>Amount Outstanding</span>
                                    <span class="font-mono text-rose-600">GHS {{ number_format($invoice->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Checkout details form -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-md border border-azure-100 p-6 relative overflow-hidden" x-data="{ paymentMethod: 'momo' }">
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-azure-800 to-azure-400"></div>

                        <h3 class="text-xl font-extrabold text-azure-950 mt-1 mb-6">Payment Details</h3>

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

                        <form method="POST" action="{{ route('student.payments.process', $invoice) }}" class="space-y-6">
                            @csrf

                            <!-- Payment Amount Selection -->
                            <div>
                                <label for="amount" class="block text-sm font-bold text-azure-900 mb-1.5">Payment Amount (GHS)</label>
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
                                           placeholder="0.00" 
                                           required>
                                </div>
                                <p class="text-xs text-azure-500 mt-1.5 font-medium">You can enter a partial amount if you prefer not to pay the full balance at once.</p>
                            </div>

                            <!-- Payment Method Tabs -->
                            <div>
                                <span class="block text-sm font-bold text-azure-900 mb-2">Select Payment Method</span>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Mobile Money Tab Button -->
                                    <button type="button" 
                                            @click="paymentMethod = 'momo'"
                                            :class="paymentMethod === 'momo' ? 'border-azure-800 bg-azure-50/50 ring-2 ring-azure-800/10' : 'border-gray-200 hover:bg-gray-50/50'"
                                            class="flex flex-col items-center justify-center p-4 border rounded-xl transition-all duration-200 text-center group">
                                        <svg class="h-8 w-8 text-azure-700 mb-2 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-bold text-azure-950">Mobile Money</span>
                                    </button>

                                    <!-- Debit/Credit Card Tab Button -->
                                    <button type="button" 
                                            @click="paymentMethod = 'card'"
                                            :class="paymentMethod === 'card' ? 'border-azure-800 bg-azure-50/50 ring-2 ring-azure-800/10' : 'border-gray-200 hover:bg-gray-50/50'"
                                            class="flex flex-col items-center justify-center p-4 border rounded-xl transition-all duration-200 text-center group">
                                        <svg class="h-8 w-8 text-azure-700 mb-2 group-hover:scale-105 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="text-sm font-bold text-azure-950">Credit / Debit Card</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Payment Method Input -->
                            <input type="hidden" name="payment_method" :value="paymentMethod">

                            <!-- Mobile Money Form Details -->
                            <div x-show="paymentMethod === 'momo'" x-transition:enter="transition ease-out duration-200" class="space-y-4">
                                <div class="bg-azure-50/40 border border-azure-100 rounded-xl p-4 mb-4">
                                    <p class="text-xs text-azure-700 font-semibold leading-relaxed">
                                        Payments will process instantly. Enter your valid 10-digit mobile number to authorize the simulated USSD prompt transaction.
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-azure-900 mb-1.5">Network Provider</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-azure-50/20 transition-colors">
                                            <input type="radio" name="momo_provider" value="mtn" class="text-azure-800 focus:ring-azure-800" {{ old('momo_provider', 'mtn') === 'mtn' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-bold text-azure-950 uppercase">MTN Momo</span>
                                        </label>
                                        <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-azure-50/20 transition-colors">
                                            <input type="radio" name="momo_provider" value="telecel" class="text-azure-800 focus:ring-azure-800" {{ old('momo_provider') === 'telecel' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-bold text-azure-950 uppercase">Telecel</span>
                                        </label>
                                        <label class="border border-gray-200 rounded-xl p-3 flex items-center justify-center cursor-pointer hover:bg-azure-50/20 transition-colors">
                                            <input type="radio" name="momo_provider" value="at" class="text-azure-800 focus:ring-azure-800" {{ old('momo_provider') === 'at' ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm font-bold text-azure-950 uppercase">AT Money</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="momo_number" class="block text-sm font-bold text-azure-900 mb-1.5">Phone Number</label>
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
                            <div x-show="paymentMethod === 'card'" x-transition:enter="transition ease-out duration-200" class="space-y-4" style="display: none;">
                                <div class="bg-azure-50/40 border border-azure-100 rounded-xl p-4 mb-4">
                                    <p class="text-xs text-azure-700 font-semibold leading-relaxed">
                                        Enter your credit or debit card details to execute a simulated checkout payment. No funds will be charged.
                                    </p>
                                </div>

                                <div>
                                    <label for="card_number" class="block text-sm font-bold text-azure-900 mb-1.5">Card Number</label>
                                    <input type="text" 
                                           name="card_number" 
                                           id="card_number" 
                                           value="{{ old('card_number') }}"
                                           placeholder="16-digit card number" 
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
                                        <label for="card_cvv" class="block text-sm font-bold text-azure-900 mb-1.5">CVV Code</label>
                                        <input type="password" 
                                               name="card_cvv" 
                                               id="card_cvv" 
                                               placeholder="***" 
                                               class="w-full rounded-xl border-azure-200 text-azure-950 focus:border-azure-500 focus:ring-azure-500 focus:ring-1 text-center" 
                                               maxlength="3"
                                               :required="paymentMethod === 'card'">
                                    </div>
                                </div>
                            </div>

                            <!-- Pay Submission Button -->
                            <div class="pt-4 border-t border-azure-50">
                                <button type="submit" class="w-full inline-flex items-center justify-center py-3.5 px-6 border border-transparent rounded-xl text-sm font-bold text-white bg-gradient-to-r from-azure-800 to-azure-700 hover:from-azure-900 hover:to-azure-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azure-500 shadow-md transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Authorize Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
