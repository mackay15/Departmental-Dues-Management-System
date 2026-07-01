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
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-azure-800 font-medium" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-azure-200 focus:border-azure-400 focus:ring-azure-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
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

        @if (Route::has('register'))
            <p class="text-center text-sm text-azure-600 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold text-azure-800 hover:text-azure-600 transition-colors">Create one</a>
            </p>
        @endif
    </form>
</x-guest-layout>
