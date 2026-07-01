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

                {{-- Left: Invoice Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-md border border-azure-100 p-6 sticky top-24">
                        <h3 class="text-lg font-extrabold text-azure-950 border-b border-azure-100 pb-3 mb-4">Order Summary</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Invoice Number</p>
                                <p class="text-sm font-bold text-azure-950 font-mono mt-0.5">{{ $invoice->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Academic Session</p>
                                <p class="text-sm font-bold text-azure-900 mt-0.5">{{ $invoice->academicSession->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Student</p>
                                <p class="text-sm font-bold text-azure-900 mt-0.5">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                                <p class="text-xs text-azure-500 font-mono">{{ $invoice->student->index_number }}</p>
                            </div>

                            <div class="border-t border-azure-50 pt-3">
                                <p class="text-xs text-azure-500 font-semibold uppercase tracking-wider mb-2">Billed Items</p>
                                <ul class="space-y-2">
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
                                    <span>Total Billed</span>
                                    <span class="font-semibold font-mono">GHS {{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-emerald-600">
                                    <span>Already Paid</span>
                                    <span class="font-semibold font-mono">-GHS {{ number_format($invoice->paid_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-base font-extrabold text-azure-950 pt-1 border-t border-azure-50">
                                    <span>Amount Due</span>
                                    <span class="font-mono text-rose-600">GHS {{ number_format($invoice->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Paystack Checkout --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md border border-azure-100 p-8 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-azure-800 to-azure-400"></div>

                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-extrabold text-azure-950">Secure Checkout</h3>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Powered by Paystack</span>
                            </div>
                        </div>

                        @if(session('error'))
                            <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl mb-6 text-sm flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Payment Amount --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-azure-900 mb-2">Payment Amount (GHS)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-azure-400 font-bold text-sm pointer-events-none">GHS</span>
                                <input
                                    type="number"
                                    id="paymentAmount"
                                    step="0.01"
                                    min="1"
                                    max="{{ $invoice->balance }}"
                                    value="{{ $invoice->balance }}"
                                    class="w-full pl-14 pr-4 py-3 rounded-xl border-azure-200 text-azure-950 font-bold text-lg focus:border-azure-500 focus:ring-azure-500 focus:ring-1"
                                    placeholder="0.00"
                                >
                            </div>
                            <p class="text-xs text-azure-500 mt-1.5 font-medium">
                                You can pay a partial amount. Maximum: GHS {{ number_format($invoice->balance, 2) }}
                            </p>
                        </div>

                        {{-- Accepted Payment Methods --}}
                        <div class="mb-6">
                            <p class="text-sm font-bold text-azure-900 mb-3">Accepted Payment Methods</p>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="border border-gray-200 rounded-xl p-3 flex flex-col items-center justify-center bg-gray-50">
                                    <svg class="w-8 h-8 text-azure-700 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-azure-800">Card</span>
                                </div>
                                <div class="border border-gray-200 rounded-xl p-3 flex flex-col items-center justify-center bg-gray-50">
                                    <svg class="w-8 h-8 text-azure-700 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-azure-800">Mobile Money</span>
                                </div>
                                <div class="border border-gray-200 rounded-xl p-3 flex flex-col items-center justify-center bg-gray-50">
                                    <svg class="w-8 h-8 text-azure-700 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-azure-800">Bank Transfer</span>
                                </div>
                            </div>
                        </div>

                        {{-- Security Notice --}}
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 mb-6 flex items-start space-x-3">
                            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-emerald-800">Your payment is secure</p>
                                <p class="text-xs text-emerald-600 mt-0.5">All transactions are encrypted and processed securely by Paystack. Your card details are never stored on our servers.</p>
                            </div>
                        </div>

                        {{-- Pay Button --}}
                        <button
                            id="payNowBtn"
                            type="button"
                            onclick="initializePaystack()"
                            class="w-full inline-flex items-center justify-center py-4 px-6 border border-transparent rounded-xl text-sm font-bold text-white bg-gradient-to-r from-azure-800 to-azure-700 hover:from-azure-900 hover:to-azure-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azure-500 shadow-md transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Pay GHS <span id="btnAmount">{{ number_format($invoice->balance, 2) }}</span> Securely
                        </button>

                        <p class="text-center text-xs text-gray-400 mt-4">
                            By proceeding, you agree to our payment terms. A receipt will be generated automatically after payment.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Paystack Inline JS --}}
    <script src="https://js.paystack.co/v2/inline.js"></script>
    <script>
        const paystackPublicKey = "{{ $paystackPublicKey }}";
        const studentEmail      = "{{ auth()->user()->email }}";
        const invoiceBalance    = {{ $invoice->balance }};
        const callbackUrl       = "{{ route('student.payments.verify', $invoice) }}";
        const amountInput       = document.getElementById('paymentAmount');
        const btnAmountSpan     = document.getElementById('btnAmount');

        // Update button label as amount changes
        amountInput.addEventListener('input', function () {
            const val = parseFloat(this.value);
            if (!isNaN(val) && val > 0) {
                btnAmountSpan.textContent = val.toFixed(2);
            }
        });

        function initializePaystack() {
            const amount = parseFloat(amountInput.value);

            if (isNaN(amount) || amount < 1) {
                alert('Please enter a valid payment amount (minimum GHS 1.00).');
                return;
            }

            if (amount > invoiceBalance) {
                alert('Amount cannot exceed the outstanding balance of GHS ' + invoiceBalance.toFixed(2));
                return;
            }

            const btn = document.getElementById('payNowBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';

            const paystack = new PaystackPop();
            paystack.newTransaction({
                key: paystackPublicKey,
                email: studentEmail,
                amount: Math.round(amount * 100), // Convert GHS to pesewas
                currency: "{{ $currency }}",
                label: "{{ $invoice->invoice_number }}",
                onSuccess: function (transaction) {
                    // Redirect to backend verification with the Paystack reference
                    window.location.href = callbackUrl + '?reference=' + transaction.reference;
                },
                onCancel: function () {
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>Pay GHS <span id="btnAmount">' + amount.toFixed(2) + '</span> Securely';
                }
            });
        }
    </script>
</x-app-layout>
