<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dues Management') }}
            </h2>
            @role('HOD')
            <a href="{{ route('dues.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Add New Due
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
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                    @role('HOD')
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dues as $due)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $due->category_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">GHS {{ number_format($due->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $due->academicSession->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div>Prog: {{ $due->programme ? $due->programme->code : 'All' }}</div>
                                            <div>Level: {{ $due->academicLevel ? $due->academicLevel->level_code : 'All' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $due->due_date ? \Carbon\Carbon::parse($due->due_date)->format('M d, Y') : 'N/A' }}</td>
                                        @role('HOD')
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('dues.edit', $due) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('dues.destroy', $due) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                        @endrole
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No dues found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
