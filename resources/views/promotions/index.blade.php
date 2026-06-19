<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
                {{ __('Student Promotion Logs') }}
            </h2>
            <a href="{{ route('promotions.preview') }}" class="inline-flex items-center px-4 py-2 bg-azure-800 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-azure-700 active:bg-azure-900 focus:outline-none focus:border-azure-900 focus:ring ring-azure-300 disabled:opacity-25 transition-all duration-200 shadow-md shadow-azure-800/10">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('New Academic Promotion') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-6">
                <div class="mb-4 border-b border-azure-50 pb-4 flex justify-between items-center">
                    <p class="text-sm text-azure-600 font-medium">History of all academic level promotions run in the system.</p>
                </div>

                @if($logs->isEmpty())
                    <div class="text-center py-12">
                        <div class="p-3 bg-azure-100/50 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3 text-azure-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-azure-900">No Promotion Logs Yet</h4>
                        <p class="text-sm text-azure-500 mt-1 max-w-sm mx-auto">Promote eligible students to their next academic level to start logging history.</p>
                        <div class="mt-6">
                            <a href="{{ route('promotions.preview') }}" class="inline-flex items-center px-4 py-2 bg-azure-100 hover:bg-azure-200 text-azure-800 font-semibold rounded-xl text-sm transition-colors duration-150">
                                Start Promotion Preview
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-azure-100">
                            <thead class="bg-azure-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Date & Time</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Session</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Run By</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Students Promoted</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-azure-50">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-azure-50/10 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-azure-950">
                                            {{ $log->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-700">
                                            <span class="bg-azure-100 text-azure-850 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                                {{ $log->academicSession->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-950">
                                            {{ $log->promotedBy->name ?? 'System' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-azure-700">
                                            {{ $log->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                            <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full px-3 py-1 text-xs font-bold">
                                                {{ is_array($log->details) ? count($log->details) : 0 }} Students
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
