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
    <body class="font-sans antialiased bg-azure-50">
        <div class="min-h-screen flex flex-col md:flex-row">
            @include('layouts.navigation')

            <!-- Page Content Container -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm border-b border-azure-100 py-5 px-4 sm:px-6 lg:px-8">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-r-lg shadow-sm" role="alert">
                            <span class="block sm:inline font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="bg-red-50 border-l-4 border-red-400 text-red-800 px-4 py-3 rounded-r-lg shadow-sm" role="alert">
                            <span class="block sm:inline font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
