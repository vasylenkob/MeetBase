<x-app-layout>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-[#0c0c14] py-28">
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image: linear-gradient(to right, rgb(255,255,255) 1px, transparent 1px), linear-gradient(to bottom, rgb(255,255,255) 1px, transparent 1px); background-size: 48px 48px;"></div>
        {{-- Ambient glow --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/4 w-[800px] h-[600px] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto px-4 text-center">
            <span class="inline-flex items-center gap-2 bg-indigo-500/10 text-indigo-400 text-sm font-medium px-4 py-1.5 rounded-full mb-8 ring-1 ring-inset ring-indigo-500/20">
                🎉 Відкривай нові враження
            </span>
            <h1 class="text-5xl sm:text-7xl font-bold text-white mb-6 leading-tight tracking-tight">
                Знайди свій<br>
                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-indigo-300 bg-clip-text text-transparent">захід</span>
            </h1>
            <p class="text-lg text-gray-400 mb-10 max-w-xl mx-auto leading-relaxed">
                Концерти, конференції, фестивалі та майстер-класи — все в одному місці.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('events.index') }}"
                   class="bg-indigo-500 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-indigo-400 transition shadow-lg shadow-indigo-500/25 text-base">
                    Переглянути заходи
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="bg-white/5 text-gray-300 font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition ring-1 ring-white/10 text-base">
                        Стати організатором
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Category strip --}}
    @php
        $allCategories = \App\Models\Category::withCount(['events' => fn($q) => $q->published()->upcoming()])->get();
    @endphp
    @if($allCategories->isNotEmpty())
        <div class="border-y border-gray-800 bg-gray-900/60">
            <div class="max-w-7xl mx-auto px-4 py-3">
                <div class="flex gap-2 overflow-x-auto pb-0.5" style="scrollbar-width: none;">
                    <a href="{{ route('events.index') }}"
                       class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium bg-indigo-500 text-white">
                        Всі заходи
                    </a>
                    @foreach($allCategories as $cat)
                        <a href="{{ route('events.index', ['category' => $cat->slug]) }}"
                           class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium bg-gray-800 text-gray-400 border border-gray-700 hover:border-indigo-500/50 hover:text-indigo-400 transition">
                            {{ $cat->icon }} {{ $cat->name }}
                            @if($cat->events_count > 0)
                                <span class="text-xs text-gray-600">{{ $cat->events_count }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Upcoming events --}}
    @php
        $upcomingEvents = \App\Models\Event::with('category')->published()->orderByDesc('starts_at')->take(6)->get();
    @endphp

    <section class="max-w-7xl mx-auto px-4 py-14">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-white">Нещодавні заходи</h2>
            <a href="{{ route('events.index') }}" class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition">
                Всі заходи →
            </a>
        </div>

        @if($upcomingEvents->isEmpty())
            <div class="text-center py-24">
                <p class="text-5xl mb-4">📅</p>
                <p class="text-lg font-medium text-gray-500">Заходів ще немає</p>
                <p class="text-sm mt-1 text-gray-600">Будьте першим організатором!</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($upcomingEvents as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- CTA for organizers --}}
    @guest
        <section class="border-t border-gray-800 bg-gray-900/60">
            <div class="max-w-3xl mx-auto px-4 py-16 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-500/10 text-2xl mb-5 ring-1 ring-inset ring-indigo-500/20">🎤</div>
                <h2 class="text-2xl font-bold text-white mb-3">Ви організатор?</h2>
                <p class="text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                    Зареєструйтесь та отримайте доступ до інструментів управління заходами та продажем квитків.
                </p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 bg-indigo-500 text-white font-semibold px-8 py-3 rounded-xl hover:bg-indigo-400 transition shadow-lg shadow-indigo-500/25">
                    Створити акаунт →
                </a>
            </div>
        </section>
    @endguest

</x-app-layout>
