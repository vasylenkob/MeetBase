<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Адмін-панель</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-indigo-400">{{ $stats['users'] }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Користувачів</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-purple-400">{{ $stats['organizers'] }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Організаторів</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-sky-400">{{ $stats['events'] }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Заходів</div>
            </div>
            <a href="{{ route('admin.events', ['status' => 'pending']) }}"
               class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-5 text-center hover:bg-amber-500/15 transition">
                <div class="text-3xl font-bold text-amber-400">{{ $stats['pending'] }}</div>
                <div class="text-xs text-amber-500/80 mt-1 uppercase tracking-wider">На розгляді</div>
            </a>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-emerald-400">{{ $stats['published'] }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Опублікованих</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-rose-400">{{ $stats['registrations'] }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Реєстрацій</div>
            </div>
            <a href="{{ route('admin.comments') }}"
               class="bg-violet-500/10 border border-violet-500/20 rounded-xl p-5 text-center hover:bg-violet-500/15 transition">
                <div class="text-3xl font-bold text-violet-400">{{ $stats['comments_pending'] }}</div>
                <div class="text-xs text-violet-500/80 mt-1 uppercase tracking-wider">Коментарів</div>
            </a>
        </div>

        <!-- Quick nav -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.users') }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition flex items-center gap-4">
                <div class="text-3xl">👥</div>
                <div>
                    <div class="font-semibold text-white">Користувачі</div>
                    <div class="text-sm text-gray-500">Керування ролями та блокуванням</div>
                </div>
            </a>
            <a href="{{ route('admin.events') }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition flex items-center gap-4">
                <div class="text-3xl">🎪</div>
                <div>
                    <div class="font-semibold text-white">Заходи</div>
                    <div class="text-sm text-gray-500">Публікація та модерація</div>
                </div>
            </a>
            <a href="{{ route('admin.categories') }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition flex items-center gap-4">
                <div class="text-3xl">🏷️</div>
                <div>
                    <div class="font-semibold text-white">Категорії</div>
                    <div class="text-sm text-gray-500">Управління категоріями</div>
                </div>
            </a>
            <a href="{{ route('admin.comments') }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition flex items-center gap-4">
                <div class="text-3xl">💬</div>
                <div>
                    <div class="font-semibold text-white">Коментарі</div>
                    <div class="text-sm text-gray-500">Модерація коментарів</div>
                </div>
            </a>
        </div>

        <!-- Latest events -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-semibold text-white">Останні заходи</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="border-b border-gray-800">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Назва</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Організатор</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Категорія</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Статус</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($latestEvents as $event)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-3 font-medium text-gray-200">{{ $event->title }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $event->organizer->name }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $event->category?->name }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset
                                    @if($event->status === 'published') bg-emerald-500/15 text-emerald-400 ring-emerald-500/25
                                    @else bg-amber-500/15 text-amber-400 ring-amber-500/25 @endif">
                                    @if($event->status === 'published') Опубліковано
                                    @else На розгляді @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
