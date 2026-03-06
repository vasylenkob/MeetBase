<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Meetbase') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#0c0c14] text-gray-100">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 relative overflow-hidden">
            {{-- Ambient glow --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/3 w-[600px] h-[500px] bg-indigo-600/15 rounded-full blur-[100px] pointer-events-none"></div>

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2 text-2xl font-bold text-indigo-400 hover:text-indigo-300 transition">
                🎪 Meetbase
            </a>

            {{-- Card --}}
            <div class="relative w-full max-w-md bg-gray-900 border border-gray-800 rounded-2xl px-8 py-8 shadow-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
