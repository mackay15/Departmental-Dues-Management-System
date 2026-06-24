<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-azure-800 leading-tight tracking-tight">
                {{ __('Academic Promotion Control') }}
            </h2>
            <a href="{{ route('promotions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-azure-200 rounded-xl font-semibold text-xs text-azure-800 uppercase tracking-widest hover:bg-azure-50 focus:outline-none transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                {{ __('View Promotion Logs') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters Card -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-6 mb-8">
                <h3 class="text-lg font-bold text-azure-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-azure-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Select Promotion Parameters
                </h3>
                <form action="{{ route('promotions.preview') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div>
                        <x-input-label for="academic_session_id" :value="__('Academic Session')" class="text-azure-700 font-semibold" />
                        <select name="academic_session_id" id="academic_session_id" class="mt-1 block w-full rounded-xl border-azure-200 focus:border-azure-400 focus:ring focus:ring-azure-250 focus:ring-opacity-50 text-sm text-azure-900 bg-azure-50/20" required>
                            <option value="">Select Session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ request('academic_session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }} {{ $session->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="programme_id" :value="__('Programme')" class="text-azure-700 font-semibold" />
                        <select name="programme_id" id="programme_id" class="mt-1 block w-full rounded-xl border-azure-200 focus:border-azure-400 focus:ring focus:ring-azure-250 focus:ring-opacity-50 text-sm text-azure-900 bg-azure-50/20" required>
                            <option value="">Select Programme</option>
                            @foreach($programmes as $prog)
                                <option value="{{ $prog->id }}" {{ request('programme_id') == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->name }} ({{ $prog->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="from_level_id" :value="__('Current Level')" class="text-azure-700 font-semibold" />
                        <select name="from_level_id" id="from_level_id" class="mt-1 block w-full rounded-xl border-azure-200 focus:border-azure-400 focus:ring focus:ring-azure-250 focus:ring-opacity-50 text-sm text-azure-900 bg-azure-50/20" required>
                            <option value="">Select Level</option>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->id }}" {{ request('from_level_id') == $lvl->id ? 'selected' : '' }}>
                                    {{ $lvl->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-azure-800 hover:bg-azure-700 text-white font-bold text-sm rounded-xl transition-all duration-150 shadow-md shadow-azure-800/10">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Preview Class
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Results -->
            @if(request()->has(['academic_session_id', 'programme_id', 'from_level_id']))
                <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-6">
                    
                    <!-- Meta Summary Banner -->
                    <div class="bg-azure-50/70 border border-azure-100 rounded-2xl p-5 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-azure-950">Promotion Preview</h4>
                            <p class="text-sm text-azure-600 mt-1">
                                Promoting students of <span class="font-semibold text-azure-800">{{ $selectedProgramme->name }}</span> 
                                from <span class="font-semibold text-azure-800">{{ $selectedLevel->name }}</span> to 
                                <span class="font-bold text-azure-900">{{ $nextLevel->name ?? 'Graduation Status' }}</span>.
                            </p>
                        </div>
                        <div class="flex gap-4">
                            <div class="bg-white px-4 py-2.5 rounded-xl shadow-sm border border-azure-100 text-center">
                                <div class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Total Class Size</div>
                                <div class="text-xl font-bold text-azure-900 mt-0.5">{{ count($students) }}</div>
                            </div>
                            <div class="bg-white px-4 py-2.5 rounded-xl shadow-sm border border-azure-100 text-center">
                                <div class="text-xs text-azure-500 font-semibold uppercase tracking-wider">Eligible for Promotion</div>
                                <div class="text-xl font-bold text-emerald-600 mt-0.5">
                                    {{ collect($students)->where('is_eligible', true)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(empty($students))
                        <div class="text-center py-12">
                            <div class="p-3 bg-azure-100/50 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3 text-azure-800">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-azure-900">No Students Found</h4>
                            <p class="text-sm text-azure-500 mt-1">No active students are currently registered in this level for the selected programme.</p>
                        </div>
                    @else
                        <form action="{{ route('promotions.promote') }}" method="POST" id="promotion-form">
                            @csrf
                            <input type="hidden" name="academic_session_id" value="{{ $selectedSession->id }}">
                            <input type="hidden" name="programme_id" value="{{ $selectedProgramme->id }}">
                            <input type="hidden" name="from_level_id" value="{{ $selectedLevel->id }}">
                            <input type="hidden" name="to_level_id" value="{{ $nextLevel->id ?? '' }}">

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-azure-100">
                                    <thead class="bg-azure-50/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-4 text-left w-12">
                                                <input type="checkbox" id="select-all-eligible" class="rounded border-azure-200 text-azure-800 focus:ring-azure-400">
                                            </th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Student Number</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Outstanding Dues</th>
                                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-azure-800 uppercase tracking-wider">Clearance Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-azure-50">
                                        @foreach($students as $student)
                                            <tr class="hover:bg-azure-50/10 transition-colors duration-150 {{ !$student->is_eligible ? 'bg-rose-50/10' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($student->is_eligible)
                                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="eligible-checkbox rounded border-azure-200 text-azure-800 focus:ring-azure-400">
                                                    @else
                                                        <input type="checkbox" disabled class="rounded border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed">
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-azure-950">
                                                    {{ $student->index_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-azure-900 font-medium">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $student->outstanding_balance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                                    GHS {{ number_format($student->outstanding_balance, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($student->is_eligible)
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                                                            </svg>
                                                            Cleared (Eligible)
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100" title="All invoices must be fully settled before academic level promotion is permitted.">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                            Blocked (Unpaid Dues)
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if(collect($students)->where('is_eligible', true)->count() > 0)
                                <div class="mt-8 border-t border-azure-50 pt-6 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-5 py-3 bg-azure-800 hover:bg-azure-700 text-white font-bold text-sm rounded-xl transition-all duration-150 shadow-md shadow-azure-800/10">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Execute Batch Promotion
                                    </button>
                                </div>
                            @else
                                <div class="mt-8 border-t border-azure-50 pt-6 text-center text-sm font-medium text-rose-600 bg-rose-50/40 p-4 rounded-xl border border-rose-150">
                                    No students are currently eligible for promotion in this class due to outstanding dues.
                                </div>
                            @endif
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Toggle scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectAllCheckbox = document.getElementById('select-all-eligible');
            if (selectAllCheckbox) {
                const checkboxes = document.querySelectorAll('.eligible-checkbox');
                selectAllCheckbox.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = selectAllCheckbox.checked;
                    });
                });
            }
        });
    </script>
</x-app-layout>
