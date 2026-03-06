<x-app-layout>
    <div class="max-w-lg mx-auto px-4 py-12">
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($registration->event->is_online)
            {{-- Online event: join confirmation card --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-2xl">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white text-center">
                    <div class="text-4xl mb-2">💻</div>
                    <h1 class="text-xl font-bold">Підтвердження реєстрації</h1>
                    <p class="text-blue-200 text-sm mt-1">Ви зареєстровані на онлайн захід</p>
                </div>

                <!-- Event info -->
                <div class="px-8 py-6 border-b border-dashed border-gray-700/60">
                    <h2 class="text-xl font-bold text-white mb-4">{{ $registration->event->title }}</h2>
                    <div class="space-y-2 text-sm text-gray-400">
                        <div class="flex gap-2">
                            <span>📅</span>
                            <span>{{ $registration->event->starts_at->format('d.m.Y, H:i') }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span>💻</span>
                            <span>Онлайн</span>
                        </div>
                        <div class="flex gap-2">
                            <span>👤</span>
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span>💳</span>
                            <span>
                                @if($registration->payment_status === 'free') Безкоштовний
                                @elseif($registration->payment_status === 'paid') Оплачено
                                @else {{ $registration->payment_status }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Join link -->
                <div class="px-8 py-6 flex flex-col items-center text-center">
                    @if($registration->event->online_url)
                        <a href="{{ $registration->event->online_url }}" target="_blank" rel="noopener noreferrer"
                           class="w-full bg-indigo-500 text-white py-3 px-6 rounded-xl hover:bg-indigo-400 transition font-medium text-center mb-3">
                            Приєднатись до заходу →
                        </a>
                    @else
                        <div class="w-full bg-gray-800 border border-gray-700 rounded-xl py-4 px-6 text-gray-500 text-sm mb-3">
                            Посилання буде надіслано організатором
                        </div>
                    @endif
                    <div class="text-xs text-gray-500 mt-2">Код бронювання: <span class="font-mono tracking-widest text-gray-400">{{ $registration->ticket_code }}</span></div>
                </div>
            </div>
        @else
            {{-- Offline event: standard QR ticket --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-2xl">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white text-center">
                    <div class="text-4xl mb-2">🎟️</div>
                    <h1 class="text-xl font-bold">Ваш квиток</h1>
                    <p class="text-indigo-200 text-sm mt-1">Пред'явіть QR-код на вході</p>
                </div>

                <!-- Event info -->
                <div class="px-8 py-6 border-b border-dashed border-gray-700/60">
                    <h2 class="text-xl font-bold text-white mb-4">{{ $registration->event->title }}</h2>
                    <div class="space-y-2 text-sm text-gray-400">
                        <div class="flex gap-2">
                            <span>📅</span>
                            <span>{{ $registration->event->starts_at->format('d.m.Y, H:i') }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span>📍</span>
                            <span>{{ $registration->event->location }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span>👤</span>
                            <span>{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span>💳</span>
                            <span>
                                @if($registration->payment_status === 'free') Безкоштовний
                                @elseif($registration->payment_status === 'paid') Оплачено
                                @else {{ $registration->payment_status }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="px-8 py-6 flex flex-col items-center">
                    <div class="mb-4 bg-white p-3 rounded-xl">
                        {!! $qrCode !!}
                    </div>
                    <div class="font-mono text-sm text-gray-500 tracking-widest">{{ $registration->ticket_code }}</div>
                </div>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('dashboard') }}" class="text-indigo-400 hover:text-indigo-300 text-sm transition">
                ← Повернутись до кабінету
            </a>
        </div>
    </div>
</x-app-layout>
