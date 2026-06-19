<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-sm text-blue-700">
                            <strong>How it works:</strong> Selecting a session and programme will automatically find all Active Students matching the criteria. It will tally up the specific Dues they owe, and generate individual Invoices for them. Students who already have an invoice for this session will be safely skipped.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('invoices.store_generation') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="academic_session_id" :value="__('Select Academic Session')" />
                            <select id="academic_session_id" name="academic_session_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ $session->is_active ? 'selected' : '' }}>
                                        {{ $session->name }} ({{ $session->is_active ? 'Active' : 'Inactive' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('academic_session_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="programme_id" :value="__('Filter by Programme (Optional)')" />
                            <select id="programme_id" name="programme_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Programmes</option>
                                @foreach($programmes as $programme)
                                    <option value="{{ $programme->id }}">
                                        {{ $programme->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-xs text-gray-500">If left as "All Programmes", invoices will be generated for every active student.</span>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('invoices.index') }}" class="text-gray-600 mr-4">Cancel</a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700" onclick="return confirm('Are you sure you want to run the billing generator?')">
                                {{ __('Generate Bulk Invoices') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
