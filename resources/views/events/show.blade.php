<x-app-layout>
    @if($event->status !== 'published')
        <div class="bg-yellow-500/10 text-yellow-300 border-b border-yellow-500/20 px-4 py-3 text-sm text-center font-medium">
            ⚠️ Попередній перегляд — захід ще не опублікований (статус: <strong>На розгляді</strong>).
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.events') }}" class="underline ml-2">← Адмін-панель</a>
            @else
                <a href="{{ route('organizer.events.show', $event) }}" class="underline ml-2">← Повернутись</a>
            @endif
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 py-10">
        <!-- Flash messages -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main content -->
            <div class="lg:col-span-2">
                @if($event->image)
                    <img src="{{ Storage::disk('public')->url($event->image) }}" alt="{{ $event->title }}"
                         class="w-full h-72 object-cover rounded-2xl mb-6">
                @else
                    <div class="w-full h-72 bg-gradient-to-br from-indigo-900/60 to-purple-900/60 rounded-2xl mb-6 flex items-center justify-center text-8xl">
                        {{ $event->category?->icon ?? '🎪' }}
                    </div>
                @endif

                <div class="flex items-center gap-2 mb-3">
                    <span class="text-sm font-medium bg-indigo-500/15 text-indigo-300 px-3 py-1 rounded-full ring-1 ring-inset ring-indigo-500/25">
                        {{ $event->category?->name }}
                    </span>
                    @if($event->isPast())
                        <span class="text-sm font-medium bg-gray-500/15 text-gray-400 px-3 py-1 rounded-full ring-1 ring-inset ring-gray-500/25">
                            Завершено
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-white mb-4">{{ $event->title }}</h1>

                <div class="text-gray-300 leading-relaxed mb-8">
                    {!! nl2br(e($event->description)) !!}
                </div>

                <!-- Comments -->
                <div class="mb-8">
                    @livewire('event-comments', ['event' => $event])
                </div>

                <!-- Map (offline events only) -->
                @if(!$event->is_online && $event->latitude && $event->longitude)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">📍 Місце проведення</h3>
                        <div id="map" class="w-full h-64 rounded-xl border border-gray-700 isolate"></div>
                    </div>

                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const map = L.map('map').setView([{{ $event->latitude }}, {{ $event->longitude }}], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap'
                            }).addTo(map);
                            L.marker([{{ $event->latitude }}, {{ $event->longitude }}])
                                .addTo(map)
                                .bindPopup('<b>{{ e($event->location) }}</b><br>{{ e($event->address ?? '') }}')
                                .openPopup();
                        });
                    </script>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sticky top-6">
                    <!-- Price -->
                    <div class="text-3xl font-bold text-white mb-4">
                        @if($event->isFree())
                            <span class="text-emerald-400">Безкоштовно</span>
                        @else
                            {{ number_format($event->price, 0) }} грн
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="space-y-3 mb-6 text-sm text-gray-400">
                        <div class="flex items-start gap-2">
                            <span class="text-lg">📅</span>
                            <div>
                                <div class="font-medium text-gray-200">{{ $event->starts_at->format('d.m.Y') }}</div>
                                <div>{{ $event->starts_at->format('H:i') }} — {{ $event->ends_at->format('H:i') }}</div>
                            </div>
                        </div>
                        @if($event->is_online)
                            <div class="flex items-start gap-2">
                                <span class="text-lg">💻</span>
                                <div>
                                    <div class="font-medium text-gray-200">Онлайн</div>
                                    @if($event->online_url)
                                        @php
                                            $canSeeLink = $registration
                                                || (auth()->check() && auth()->user()->isAdmin())
                                                || (auth()->check() && $event->user_id === auth()->id());
                                        @endphp
                                        @if($canSeeLink)
                                            <a href="{{ $event->online_url }}" target="_blank" rel="noopener noreferrer"
                                               class="text-indigo-400 text-xs hover:text-indigo-300 hover:underline">
                                                Приєднатись →
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-500">Посилання доступне після реєстрації</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-2">
                                <span class="text-lg">📍</span>
                                <div>
                                    <div class="font-medium text-gray-200">{{ $event->location }}</div>
                                    @if($event->address)
                                        <div>{{ $event->address }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <span class="text-lg">👥</span>
                            <div>
                                <span class="font-medium text-gray-200">{{ $event->registrations()->where('status','active')->count() }}</span> / {{ $event->capacity }} зареєструвалися
                                <div class="text-xs text-gray-500">залишилось {{ $event->spotsLeft() }} місць</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg">🎤</span>
                            <div>Організатор: <span class="font-medium text-gray-200">{{ $event->organizer->name }}</span></div>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 mb-5"></div>

                    <!-- Action button -->
                    @if($event->isPast())
                        <div class="w-full text-center bg-gray-800 text-gray-500 py-3 rounded-xl font-medium border border-gray-700">
                            Захід завершено
                        </div>
                        @if($registration)
                            <a href="{{ route('tickets.show', $registration) }}"
                               class="block w-full text-center text-sm text-indigo-400 hover:text-indigo-300 hover:underline mt-3">
                                {{ $registration->event->is_online ? 'Переглянути деталі' : 'Переглянути квиток' }}
                            </a>
                        @endif
                    @elseif($registration)
                        <div class="text-center text-emerald-400 font-semibold mb-3">✓ Ви зареєстровані</div>
                        <a href="{{ route('tickets.show', $registration) }}"
                           class="block w-full text-center bg-indigo-500 text-white py-3 rounded-xl hover:bg-indigo-400 transition font-medium mb-3">
                            Переглянути квиток
                        </a>
                        <form method="POST" action="{{ route('registrations.cancel', $registration) }}">
                            @csrf @method('PATCH')
                            <button type="submit" onclick="return confirm('Скасувати реєстрацію?')"
                                    class="w-full text-sm text-gray-500 hover:text-red-400 transition py-2">
                                Скасувати реєстрацію
                            </button>
                        </form>
                    @elseif($event->isFull())
                        <div class="w-full text-center bg-gray-800 text-gray-500 py-3 rounded-xl font-medium border border-gray-700">
                            Місць немає
                        </div>
                    @elseif(auth()->check() && auth()->user()->isAttendee())
                        <form method="POST" action="{{ route('registrations.store', $event->slug) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-indigo-500 text-white py-3 rounded-xl hover:bg-indigo-400 transition font-medium">
                                @if($event->isFree()) Зареєструватись @else Придбати квиток @endif
                            </button>
                        </form>
                    @elseif(auth()->check())
                        <div class="w-full text-center text-sm text-gray-500 py-3">
                            Реєстрація доступна лише для відвідувачів
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="block w-full text-center bg-indigo-500 text-white py-3 rounded-xl hover:bg-indigo-400 transition font-medium">
                            Увійти для реєстрації
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
