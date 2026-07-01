<x-guest-layout>
    <h2 class="text-2xl font-bold text-azure-800 mb-1">Set a New Password</h2>
    <p class="text-sm text-azure-600 mb-6">Before you can continue, you must change your one-time password to a secure, preferred password.</p>

    <!-- Validation Errors -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.force_change.store') }}">
        @csrf

        <!-- Temporary / Current Password -->
        <div>
            <x-input-label for="current_password" :value="__('Current One-Time Password')" class="text-azure-800 font-medium" />
            <x-text-input id="current_password" class="block mt-1 w-full rounded-lg border-azure-200 focus:border-azure-400 focus:ring-azure-400" 
                            type="password" 
                            name="current_password" 
                            required autofocus autocomplete="current-password" 
                            placeholder="Enter the password provided to you" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('New Password')" class="text-azure-800 font-medium" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-azure-200 focus:border-azure-400 focus:ring-azure-400"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="At least 8 characters" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm New Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-azure-800 font-medium" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-azure-200 focus:border-azure-400 focus:ring-azure-400"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password"
                            placeholder="Confirm your new password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 flex flex-col gap-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-semibold text-white bg-azure-800 hover:bg-azure-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azure-500 transition-all duration-200 shadow-md hover:shadow-lg">
                {{ __('Update Password & Continue') }}
            </button>
        </div>
    </form>

    <div class="mt-6 border-t border-azure-100 pt-4 flex justify-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-azure-600 hover:text-azure-800 font-medium transition-colors underline">
                {{ __('Cancel and Sign Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
