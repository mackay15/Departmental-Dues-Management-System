<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HTU COMPSSA SFMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex bg-azure-800">
            <!-- Left Panel - Branding -->
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-center items-center px-12 relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-azure-700 opacity-50"></div>
                <div class="absolute -bottom-32 -right-20 w-96 h-96 rounded-full bg-azure-900 opacity-40"></div>
                <div class="absolute top-1/3 right-10 w-40 h-40 rounded-full bg-azure-100 opacity-10"></div>
                
                <div class="relative z-10 text-center">
                    <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" class="h-24 w-24 rounded-full object-cover mx-auto mb-6 shadow-lg border-2 border-azure-400">
                    <h1 class="text-4xl font-bold text-white mb-3">COMPSSA SFMS</h1>
                    <p class="text-azure-200 text-lg leading-relaxed max-w-md">Student Finance Management System for the Computer Science Students Association</p>
                    <div class="mt-8 flex items-center justify-center space-x-3">
                        <div class="w-12 h-1 rounded-full bg-azure-100"></div>
                        <div class="w-3 h-1 rounded-full bg-azure-400"></div>
                        <div class="w-3 h-1 rounded-full bg-azure-400"></div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-azure-50 px-6 sm:px-12">
                <!-- Mobile logo -->
                <div class="lg:hidden mb-8 text-center">
                    <img src="{{ asset('images/compssa_logo.png') }}" alt="COMPSSA Logo" class="h-16 w-16 rounded-full object-cover mx-auto mb-3 shadow-md border border-azure-300">
                    <h1 class="text-2xl font-bold text-azure-800">COMPSSA SFMS</h1>
                </div>

                <div class="w-full sm:max-w-md">
                    <div class="bg-white shadow-xl rounded-2xl px-8 py-10 border border-azure-100">
                        {{ $slot }}
                    </div>
                    <p class="text-center text-sm text-azure-600 mt-6">&copy; {{ date('Y') }} HTU COMPSSA. All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>
