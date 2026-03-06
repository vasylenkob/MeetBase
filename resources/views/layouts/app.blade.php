<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Meetbase') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#0c0c14] text-gray-100 flex flex-col min-h-screen">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-gray-900 border-b border-gray-800">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="mt-20 border-t border-gray-800 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="font-bold text-base text-indigo-400 tracking-tight">🎪 Meetbase</a>
                <span>© {{ date('Y') }} Meetbase. Всі права захищені.</span>
                <div class="flex gap-5">
                    <a href="{{ route('events.index') }}" class="hover:text-gray-300 transition">Заходи</a>
                    @guest
                        <a href="{{ route('login') }}" class="hover:text-gray-300 transition">Вхід</a>
                        <a href="{{ route('register') }}" class="hover:text-gray-300 transition">Реєстрація</a>
                    @endguest
                </div>
            </div>
        </footer>
    </body>
</html>
