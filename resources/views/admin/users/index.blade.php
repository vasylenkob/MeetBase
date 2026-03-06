<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Користувачі</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <!-- Filters -->
        <form method="GET" class="flex gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Пошук за ім'ям або email..."
                   class="flex-1 bg-gray-800 border-gray-700 text-gray-200 placeholder-gray-500 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select name="role" class="bg-gray-800 border-gray-700 text-gray-200 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Всі ролі</option>
                <option value="admin"     @selected(request('role') === 'admin')>Адмін</option>
                <option value="organizer" @selected(request('role') === 'organizer')>Організатор</option>
                <option value="attendee"  @selected(request('role') === 'attendee')>Відвідувач</option>
            </select>
            <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-400 transition">Фільтр</button>
        </form>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-800">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Користувач</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Роль</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Статус</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Зареєстрований</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-200">{{ $user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <select name="role" class="text-sm bg-gray-800 border-gray-700 text-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="admin"     @selected($user->role === 'admin')>Адмін</option>
                                        <option value="organizer" @selected($user->role === 'organizer')>Організатор</option>
                                        <option value="attendee"  @selected($user->role === 'attendee')>Відвідувач</option>
                                    </select>
                                    <button type="submit" class="text-xs text-indigo-400 hover:text-indigo-300 transition">Зберегти</button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_blocked)
                                    <span class="bg-red-500/15 text-red-400 ring-1 ring-inset ring-red-500/25 px-2 py-0.5 rounded-full text-xs font-medium">Заблокований</span>
                                @else
                                    <span class="bg-emerald-500/15 text-emerald-400 ring-1 ring-inset ring-emerald-500/25 px-2 py-0.5 rounded-full text-xs font-medium">Активний</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('d.m.Y') }}</td>
                            <td class="px-6 py-4">
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="text-sm transition {{ $user->is_blocked ? 'text-emerald-400 hover:text-emerald-300' : 'text-red-500/70 hover:text-red-400' }}">
                                            {{ $user->is_blocked ? 'Розблокувати' : 'Заблокувати' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>
