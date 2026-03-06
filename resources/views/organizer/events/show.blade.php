<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">{{ $event->title }}</h2>
            <div class="flex gap-3">
                <a href="{{ route('organizer.events.edit', $event) }}"
                   class="bg-gray-800 border border-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">
                    Редагувати
                </a>
                <a href="{{ route('events.show', $event->slug) }}" target="_blank"
                   class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-400 transition">
                    Переглянути
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-indigo-400">{{ $event->registrations->where('status','active')->count() }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Учасників</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-white">{{ $event->capacity }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Місць всього</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center">
                <div class="text-3xl font-bold text-emerald-400">{{ $event->spotsLeft() }}</div>
                <div class="text-xs text-gray-500 mt-1 uppercase tracking-wider">Залишилось</div>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 text-center flex flex-col items-center justify-center gap-2">
                <span class="text-xs font-medium px-3 py-1 rounded-full ring-1 ring-inset
                    @if($event->status === 'published') bg-emerald-500/15 text-emerald-400 ring-emerald-500/25
                    @else bg-amber-500/15 text-amber-400 ring-amber-500/25 @endif">
                    @if($event->status === 'published') Опубліковано
                    @else На розгляді @endif
                </span>
                <div class="text-xs text-gray-500 uppercase tracking-wider">Статус</div>
            </div>
        </div>

        <!-- Attendees list -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-semibold text-white">Список учасників</h3>
            </div>

            @if($event->registrations->where('status','active')->isEmpty())
                <div class="text-center py-10 text-gray-500">
                    Ще ніхто не зареєструвався
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-800">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Учасник</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Email</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Код квитка</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Оплата</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Дата реєстрації</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($event->registrations->where('status','active') as $reg)
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-3 font-medium text-gray-200">{{ $reg->user->name }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $reg->user->email }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-400">{{ $reg->ticket_code }}</td>
                                <td class="px-6 py-3">
                                    @if($reg->payment_status === 'free')
                                        <span class="bg-emerald-500/15 text-emerald-400 ring-1 ring-inset ring-emerald-500/25 px-2 py-0.5 rounded-full text-xs">Безкоштовно</span>
                                    @elseif($reg->payment_status === 'paid')
                                        <span class="bg-sky-500/15 text-sky-400 ring-1 ring-inset ring-sky-500/25 px-2 py-0.5 rounded-full text-xs">Оплачено</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $reg->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
