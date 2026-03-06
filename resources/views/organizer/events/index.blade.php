<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">Мої заходи</h2>
            <a href="{{ route('organizer.events.create') }}"
               class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-400 transition font-medium">
                + Новий захід
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        @if($events->isEmpty())
            <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                <p class="text-5xl mb-4">🎪</p>
                <p class="text-lg font-medium text-gray-300">Ви ще не створили жодного заходу</p>
                <a href="{{ route('organizer.events.create') }}" class="mt-4 inline-block text-indigo-400 hover:text-indigo-300 hover:underline">
                    Створити перший →
                </a>
            </div>
        @else
            <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-800">
                        <tr>
                            <th class="text-left px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Назва</th>
                            <th class="text-left px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Дата</th>
                            <th class="text-left px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Статус</th>
                            <th class="text-left px-5 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Учасники</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-5 py-4 font-medium text-gray-200">{{ $event->title }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $event->starts_at->format('d.m.Y') }}</td>
                                <td class="px-5 py-4">
                                    @if($event->ends_at->isPast())
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-500/15 text-gray-400 ring-1 ring-inset ring-gray-500/25">Завершено</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset
                                            @if($event->status === 'published') bg-emerald-500/15 text-emerald-400 ring-emerald-500/25
                                            @else bg-amber-500/15 text-amber-400 ring-amber-500/25 @endif">
                                            @if($event->status === 'published') Опубліковано
                                            @else На розгляді @endif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-400">{{ $event->registrations_count }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 justify-end">
                                        <a href="{{ route('organizer.events.show', $event) }}" class="text-indigo-400 hover:text-indigo-300 transition">Деталі</a>
                                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-gray-500 hover:text-gray-300 transition">Ред.</a>
                                        <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Видалити захід?')"
                                                    class="text-red-500/60 hover:text-red-400 transition">Вид.</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $events->links() }}</div>
        @endif
    </div>
</x-app-layout>
