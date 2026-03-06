<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Кабінет організатора</h2>
            <a href="{{ route('organizer.events.create') }}"
               class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-400 transition font-medium">
                + Новий захід
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Мої останні заходи</h3>

        @if($events->isEmpty())
            <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                <p class="text-5xl mb-4">🎪</p>
                <p class="text-lg font-medium text-gray-300">У вас ще немає заходів</p>
                <a href="{{ route('organizer.events.create') }}" class="mt-4 inline-block text-indigo-400 hover:text-indigo-300 hover:underline">
                    Створити перший захід →
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($events as $event)
                    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 flex items-center justify-between gap-4 hover:border-gray-700 transition">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-white">{{ $event->title }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium ring-1 ring-inset
                                    @if($event->status === 'published') bg-emerald-500/15 text-emerald-400 ring-emerald-500/25
                                    @elseif($event->status === 'cancelled') bg-red-500/15 text-red-400 ring-red-500/25
                                    @else bg-amber-500/15 text-amber-400 ring-amber-500/25 @endif">
                                    @if($event->status === 'published') Опубліковано
                                    @elseif($event->status === 'cancelled') Скасовано
                                    @else На розгляді @endif
                                </span>
                                @if($event->ends_at->isPast())
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-gray-500/15 text-gray-400 ring-1 ring-inset ring-gray-500/25">
                                        Завершено
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $event->starts_at->format('d.m.Y H:i') }} ·
                                {{ $event->registrations_count }} учасників
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('organizer.events.show', $event) }}"
                               class="text-sm text-indigo-400 hover:text-indigo-300 transition">Деталі</a>
                            <a href="{{ route('organizer.events.edit', $event) }}"
                               class="text-sm bg-gray-800 text-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-700 transition">
                                Редагувати
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 text-center">
                <a href="{{ route('organizer.events') }}" class="text-indigo-400 hover:text-indigo-300 text-sm transition">
                    Всі мої заходи →
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
