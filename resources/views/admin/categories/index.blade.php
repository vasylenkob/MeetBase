<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Категорії</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <!-- Add form -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
            <h3 class="font-semibold text-white mb-4">Нова категорія</h3>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <x-input-label for="name" value="Назва *" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                  value="{{ old('name') }}" placeholder="Наприклад: Концерт" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>
                <div class="w-24">
                    <x-input-label for="icon" value="Іконка" />
                    <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full text-center"
                                  value="{{ old('icon') }}" placeholder="🎵" maxlength="2" />
                </div>
                <button type="submit"
                        class="bg-indigo-500 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-400 transition font-medium">
                    Додати
                </button>
            </form>
        </div>

        <!-- List -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            @if($categories->isEmpty())
                <div class="text-center py-10 text-gray-500">Категорій ще немає</div>
            @else
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-800">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Іконка</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Назва</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Заходів</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($categories as $category)
                            <tr x-data="{ editing: false }" class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-3 text-2xl">{{ $category->icon ?? '🏷️' }}</td>
                                <td class="px-6 py-3">
                                    <span x-show="!editing" class="font-medium text-gray-200">{{ $category->name }}</span>
                                    <form x-show="editing" method="POST" action="{{ route('admin.categories.update', $category) }}"
                                          class="flex items-center gap-2" style="display:none">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}"
                                               class="text-sm bg-gray-800 border-gray-700 text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                        <input type="text" name="icon" value="{{ $category->icon }}"
                                               class="w-16 text-sm text-center bg-gray-800 border-gray-700 text-gray-200 rounded-lg" maxlength="2">
                                        <button type="submit" class="text-xs text-emerald-400 hover:text-emerald-300 transition">Зберегти</button>
                                    </form>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $category->events_count }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3 justify-end">
                                        <button @click="editing = !editing" type="button"
                                                class="text-indigo-400 hover:text-indigo-300 text-xs transition">Ред.</button>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Видалити?')"
                                                    class="text-red-500/60 hover:text-red-400 text-xs transition">Вид.</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
