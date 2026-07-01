<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Search Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Find Student</h3>
                    <form method="GET" action="{{ route('payments.record') }}" class="flex">
                        <input type="text" name="index_number" value="{{ request('index_number') }}" placeholder="Enter Student Index Number" required class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-azure-500 focus:ring-azure-500">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-azure-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-azure-700 focus:bg-azure-700 active:bg-azure-900 focus:outline-none focus:ring-2 focus:ring-azure-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Search
                        </button>
                    </form>
                </div>
            </div>

            <!-- Student & Invoices Details -->
            @if(request('index_number') && isset($student))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center">
                            @if($student->photo_path)
                                <img class="h-16 w-16 rounded-full object-cover mr-4 ring-2 ring-azure-500" src="{{ Storage::url($student->photo_path) }}" alt="{{ $student->first_name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-azure-100 flex items-center justify-center mr-4 text-azure-700 font-bold text-xl ring-2 ring-azure-500">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $student->first_name }} {{ $student->other_names }} {{ $student->last_name }}</h3>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Index Number:</span> {{ $student->index_number }} |
                                    <span class="font-semibold">Programme:</span> {{ $student->programme->name }} |
                                    <span class="font-semibold">Level:</span> {{ $student->currentLevel->name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Pending Invoices</h4>
                        
                        @if($invoices->isEmpty())
                            <div class="bg-green-50 text-green-800 p-4 rounded-md shadow-sm border border-green-200">
                                <p class="flex items-center text-md font-medium">
                                    <svg class="w-6 h-6 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    This student has no pending invoices!
                                </p>
                                <p class="mt-2 text-sm text-green-700 ml-9">
                                    This means they either have not been billed yet for the current session, or they have already fully paid all their dues.
                                </p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($invoices as $invoice)
                                    <div class="flex items-center justify-between p-4 border {{ $invoice->status === 'unpaid' ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50' }} rounded-lg">
                                        <div>
                                            <h5 class="font-bold text-gray-900">Session: {{ $invoice->academicSession->name }}</h5>
                                            <p class="text-sm text-gray-600">Invoice #: {{ $invoice->invoice_number }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                @foreach($invoice->items as $item)
                                                    {{ $item->due->title }}@if(!$loop->last), @endif
                                                @endforeach
                                            </p>
                                            <div class="mt-2 flex items-center space-x-4 text-sm">
                                                <span class="text-gray-500">Total: GHS {{ number_format($invoice->total_amount, 2) }}</span>
                                                <span class="text-green-600">Paid: GHS {{ number_format($invoice->paid_amount, 2) }}</span>
                                                <span class="font-bold text-red-600 text-base">Balance: GHS {{ number_format($invoice->balance, 2) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('payments.create', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                Pay Now
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
