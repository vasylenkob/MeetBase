<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Доступ заборонено — Meetbase</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-6 py-12 text-center">

        @php
            $message = $exception?->getMessage() ?? '';
            $isBlocked = str_contains($message, 'заблоковано');
        @endphp

        @if($isBlocked)
            <!-- Banned user -->
            <div class="text-7xl mb-6">🚫</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Акаунт заблоковано</h1>
            <p class="text-gray-600 mb-6 leading-relaxed">
                {{ $message }}
            </p>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8 text-sm text-red-700">
                Всі ваші дані збережено. Після розблокування акаунту ви матимете повний доступ до платформи.
            </div>
        @else
            <!-- Access denied (wrong role etc.) -->
            <div class="text-7xl mb-6">🔒</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Доступ заборонено</h1>
            <p class="text-gray-600 mb-6 leading-relaxed">
                @if($message)
                    {{ $message }}
                @else
                    У вас немає прав для перегляду цієї сторінки.
                @endif
            </p>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="inline-block bg-indigo-600 text-white font-medium px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition">
                На головну
            </a>

            @auth
                @if(!$isBlocked)
                    <a href="{{ url()->previous() }}"
                       class="inline-block border border-gray-300 text-gray-700 font-medium px-6 py-2.5 rounded-xl hover:bg-gray-50 transition">
                        Назад
                    </a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full border border-gray-300 text-gray-700 font-medium px-6 py-2.5 rounded-xl hover:bg-gray-50 transition">
                            Вийти з акаунту
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        <p class="mt-8 text-xs text-gray-400">Meetbase · Помилка 403</p>
    </div>
</body>
</html>
