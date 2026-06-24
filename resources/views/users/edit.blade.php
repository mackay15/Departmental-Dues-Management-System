<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                &larr; Back to Users
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit User — {{ $user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Edit Name / Email / Role --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Account Details</h3>

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-5">
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                    {{ $errors->has('name') ? 'border-red-400' : '' }}">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                    {{ $errors->has('email') ? 'border-red-400' : '' }}">
                        </div>

                        {{-- Role --}}
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" name="role" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                    {{ $errors->has('role') ? 'border-red-400' : '' }}">
                                <option value="">— Select a role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ (old('role') ?? $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('users.index') }}"
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Account Status Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Account Status</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Current status:
                        <span class="font-semibold {{ $user->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
                        </span>
                    </p>

                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('users.toggle-status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            onclick="return confirm('{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }} this account?')"
                            class="px-4 py-2 text-sm font-medium text-white rounded-md transition
                                {{ $user->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                            {{ $user->status === 'active' ? 'Deactivate Account' : 'Activate Account' }}
                        </button>
                    </form>
                    @else
                        <p class="text-sm text-gray-400 italic">You cannot change the status of your own account.</p>
                    @endif
                </div>
            </div>

            {{-- Reset Password Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Reset Password</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Set a new password for this user. They will need to use the new password on their next login.
                    </p>

                    @if(session('pwd_success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                            {{ session('pwd_success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.reset-password', $user) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="new_password" name="new_password" required minlength="8"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm
                                    {{ $errors->has('new_password') ? 'border-red-400' : '' }}"
                                placeholder="Minimum 8 characters">
                            @error('new_password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div class="pt-1">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700 transition">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
