<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-gray-700 mr-2">
                    &larr; Back
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Student Profile') }}
                </h2>
            </div>
            <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                Edit Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex flex-col md:flex-row">
                    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-8 flex flex-col items-center">
                        @if($student->photo_path)
                            <img src="{{ Storage::url($student->photo_path) }}" alt="Photo" class="h-40 w-40 rounded-full object-cover shadow-lg border-4 border-gray-100">
                        @else
                            <div class="h-40 w-40 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-5xl shadow-lg border-4 border-gray-100">
                                {{ substr($student->first_name, 0, 1) }}
                            </div>
                        @endif
                        <span class="mt-4 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>

                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-bold border-b pb-2 mb-3">Personal Details</h3>
                            <p class="mb-1"><span class="font-semibold text-gray-600">Name:</span> {{ $student->first_name }} {{ $student->other_names }} {{ $student->last_name }}</p>
                            <p class="mb-1"><span class="font-semibold text-gray-600">Email:</span> {{ $student->email }}</p>
                            <p class="mb-1"><span class="font-semibold text-gray-600">Phone:</span> {{ $student->phone ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold border-b pb-2 mb-3">Academic Details</h3>
                            <p class="mb-1"><span class="font-semibold text-gray-600">Index Number:</span> {{ $student->index_number }}</p>
                            <p class="mb-1"><span class="font-semibold text-gray-600">Programme:</span> {{ $student->programme->name ?? 'N/A' }}</p>
                            <p class="mb-1"><span class="font-semibold text-gray-600">Current Level:</span> {{ $student->currentLevel->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- In the future, we can add tabs here for Invoices, Payments, Promotion Logs, etc. -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Academic History</h3>
                    
                    @if($student->academicRecords->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($student->academicRecords as $record)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $record->academicSession->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $record->academicLevel->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ ucfirst($record->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500 italic">No academic history recorded yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
