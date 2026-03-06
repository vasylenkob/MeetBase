<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Управління заходами</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <form method="GET" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Пошук за назвою..."
                   class="flex-1 bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="status" class="bg-gray-800 border-gray-700 text-gray-200 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Всі статуси</option>
                <option value="pending" @selected(request('status') === 'pending')>На розгляді</option>
                <option value="published" @selected(request('status') === 'published')>Опублікований</option>
            </select>
            <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-400 transition">Фільтр</button>
        </form>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-800">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Захід</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Організатор</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Дата</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($events as $event)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-200">{{ $event->title }}</div>
                                <div class="text-gray-500 text-xs">{{ $event->category?->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $event->organizer->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $event->starts_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset
                                    @if($event->status === 'published') bg-emerald-500/15 text-emerald-400 ring-emerald-500/25
                                    @else bg-amber-500/15 text-amber-400 ring-amber-500/25 @endif">
                                    @if($event->status === 'published') Опубліковано
                                    @else На розгляді @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 justify-end">
                                    <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                                       class="text-indigo-400 hover:text-indigo-300 text-xs transition">Переглянути</a>

                                    @if($event->status === 'pending')
                                        <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="bg-emerald-500/20 text-emerald-400 text-xs px-3 py-1.5 rounded-lg hover:bg-emerald-500/30 transition">
                                                Опублікувати
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Видалити захід?')"
                                                class="text-red-500/60 hover:text-red-400 text-xs transition">Видалити</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $events->links() }}</div>
    </div>
</x-app-layout>
