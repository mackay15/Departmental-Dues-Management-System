<x-guest-layout>
    <h2 class="text-2xl font-bold text-azure-800 mb-1">Welcome back</h2>
    <p class="text-sm text-azure-600 mb-6">Sign in to your account to continue</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address or Index Number -->
        <div>
            <x-input-label for="email" :value="__('Email Address or Index Number')" class="text-azure-800 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-azure-200 focus:border-azure-400 focus:ring-azure-400" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com or Index Number" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ showPassword: false }">
            <x-input-label for="password" :value="__('Password')" class="text-azure-800 font-medium" />
            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full rounded-lg border-azure-200 focus:border-azure-400 focus:ring-azure-400 pr-10"
                                x-bind:type="showPassword ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-azure-600 hover:text-azure-800 focus:outline-none">
                    <!-- Eye Icon (when password is hidden) -->
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Off Icon (when password is visible) -->
                    <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4M9.69 9.69a3 3 0 004.62 4.62M4.93 4.93l14.14 14.14M15.47 15.47A9.973 9.973 0 0019.542 12c-1.274-4.057-5.064-7-9.542-7-1.189 0-2.32.206-3.37.585" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-azure-300 text-azure-800 shadow-sm focus:ring-azure-400" name="remember">
                <span class="ms-2 text-sm text-azure-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-azure-600 hover:text-azure-800 font-medium transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-semibold text-white bg-azure-800 hover:bg-azure-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azure-500 transition-all duration-200 shadow-md hover:shadow-lg">
                {{ __('Sign In') }}
            </button>
        </div>

    </form>
</x-guest-layout>
