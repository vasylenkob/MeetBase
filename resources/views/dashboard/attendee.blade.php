<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Мій кабінет</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

        <!-- Upcoming registrations -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Майбутні заходи</h3>

            @if($upcomingRegistrations->isEmpty())
                <div class="text-center py-12 bg-gray-900 border border-gray-800 rounded-2xl">
                    <p class="text-5xl mb-4">🎟️</p>
                    <p class="text-lg font-medium text-gray-300">У вас ще немає квитків</p>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block text-indigo-400 hover:text-indigo-300 hover:underline">
                        Переглянути заходи →
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($upcomingRegistrations as $reg)
                        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 flex items-center justify-between gap-4 hover:border-gray-700 transition">
                            <div class="flex items-center gap-4">
                                <div class="text-3xl">{{ $reg->event->category?->icon ?? '🎪' }}</div>
                                <div>
                                    <div class="font-semibold text-white">{{ $reg->event->title }}</div>
                                    <div class="text-sm text-gray-400 mt-0.5">
                                        {{ $reg->event->starts_at->format('d.m.Y H:i') }} · @if($reg->event->is_online) 💻 Онлайн @else {{ $reg->event->location }} @endif
                                    </div>
                                    <div class="text-xs mt-1.5">
                                        @if($reg->payment_status === 'free')
                                            <span class="bg-emerald-500/15 text-emerald-400 px-2 py-0.5 rounded-full ring-1 ring-inset ring-emerald-500/25">Безкоштовний</span>
                                        @elseif($reg->payment_status === 'paid')
                                            <span class="bg-indigo-500/15 text-indigo-300 px-2 py-0.5 rounded-full ring-1 ring-inset ring-indigo-500/25">Оплачено</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ route('tickets.show', $reg) }}"
                                   class="text-sm bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-400 transition">
                                    {{ $reg->event->is_online ? 'Деталі' : 'Квиток' }}
                                </a>
                                <form method="POST" action="{{ route('registrations.cancel', $reg) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('Скасувати реєстрацію?')"
                                            class="text-sm text-gray-500 hover:text-red-400 transition">
                                        Скасувати
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Past registrations -->
        @if($pastRegistrations->isNotEmpty())
            <div>
                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-4">Минулі заходи</h3>
                <div class="space-y-3">
                    @foreach($pastRegistrations as $reg)
                        <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-5 flex items-center justify-between gap-4 opacity-60">
                            <div class="flex items-center gap-4">
                                <div class="text-3xl grayscale">{{ $reg->event->category?->icon ?? '🎪' }}</div>
                                <div>
                                    <div class="font-medium text-gray-300">{{ $reg->event->title }}</div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        {{ $reg->event->starts_at->format('d.m.Y') }} · @if($reg->event->is_online) 💻 Онлайн @else {{ $reg->event->location }} @endif
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('tickets.show', $reg) }}"
                               class="text-sm text-gray-500 hover:text-indigo-400 transition shrink-0">
                                {{ $reg->event->is_online ? 'Деталі' : 'Квиток' }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
