<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Management
            </h2>
            @role('HOD')
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                + Add Staff User
            </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('users.index') }}" class="mb-6 flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                            <div class="flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Name or email..."
                                    class="rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 flex-1 text-sm">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Search
                                </button>
                            </div>
                        </div>
                        <div class="min-w-[160px]">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Filter by Role</label>
                            <select name="role" onchange="this.form.submit()"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm w-full">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('search') || request('role'))
                            <a href="{{ route('users.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                                Clear
                            </a>
                        @endif
                    </form>

                    {{-- Users Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="{{ $user->status !== 'active' ? 'bg-gray-50 opacity-75' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm mr-3">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                    @if($user->id === auth()->id())
                                                        <div class="text-xs text-indigo-500 font-medium">You</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($user->roles as $role)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($role->name === 'HOD') bg-blue-100 text-blue-800
                                                    @elseif($role->name === 'Finance Officer') bg-green-100 text-green-800
                                                    @elseif($role->name === 'Auditor') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            @role('HOD')
                                            <a href="{{ route('users.edit', $user) }}"
                                               class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                            {{-- Toggle Status --}}
                                            @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    onclick="return confirm('{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }} this account?')"
                                                    class="{{ $user->status === 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                                    {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            @endif

                                            {{-- Reset Password --}}
                                            <button type="button"
                                                onclick="openResetModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="text-yellow-600 hover:text-yellow-900">
                                                Reset Pwd
                                            </button>
                                            @endrole
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400">
                                            No staff users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Password Reset Modal --}}
    <div id="resetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Reset Password</h3>
            <p class="text-sm text-gray-500 mb-4">Resetting password for: <span id="resetUserName" class="font-medium text-gray-700"></span></p>

            <form id="resetPasswordForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="new_password" id="resetNewPassword" required minlength="8"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="resetConfirmPassword" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeResetModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const resetRouteBase = "{{ url('users') }}";

        function openResetModal(userId, userName) {
            document.getElementById('resetUserName').textContent = userName;
            document.getElementById('resetPasswordForm').action = resetRouteBase + '/' + userId + '/reset-password';
            document.getElementById('resetNewPassword').value = '';
            document.getElementById('resetConfirmPassword').value = '';
            document.getElementById('resetModal').classList.remove('hidden');
        }

        function closeResetModal() {
            document.getElementById('resetModal').classList.add('hidden');
        }

        // Close modal on backdrop click
        document.getElementById('resetModal').addEventListener('click', function(e) {
            if (e.target === this) closeResetModal();
        });
    </script>
</x-app-layout>
