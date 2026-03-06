<x-app-layout>

    {{-- Page header with filters --}}
    <div class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-white mb-6">Всі заходи</h1>

            <form method="GET" action="{{ route('events.index') }}">
                <div class="flex flex-wrap gap-3 items-center">
                    {{-- Search --}}
                    <div class="relative flex-1 min-w-56">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Пошук заходів..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-700 bg-gray-800 text-gray-200 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>

                    {{-- Category --}}
                    <select name="category" class="rounded-xl border-gray-700 bg-gray-800 text-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm py-2.5 px-3">
                        <option value="">Всі категорії</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Type --}}
                    <select name="type" class="rounded-xl border-gray-700 bg-gray-800 text-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm py-2.5 px-3">
                        <option value="">Будь-яка ціна</option>
                        <option value="free" @selected(request('type') === 'free')>Безкоштовні</option>
                        <option value="paid" @selected(request('type') === 'paid')>Платні</option>
                    </select>

                    <button type="submit"
                            class="bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-400 transition">
                        Знайти
                    </button>

                    @if(request()->hasAny(['search', 'category', 'type']))
                        <a href="{{ route('events.index') }}"
                           class="px-4 py-2.5 rounded-xl border border-gray-700 text-sm text-gray-400 hover:text-gray-200 hover:border-gray-600 transition">
                            Скинути
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Results --}}
    <div class="max-w-7xl mx-auto px-4 py-8">
        @if($events->isEmpty())
            <div class="text-center py-24">
                <p class="text-6xl mb-5">🔍</p>
                <p class="text-xl font-medium text-gray-300 mb-1">Заходів не знайдено</p>
                <p class="text-sm text-gray-500">Спробуйте змінити фільтри або <a href="{{ route('events.index') }}" class="text-indigo-400 hover:underline">скинути пошук</a></p>
            </div>
        @else
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    Знайдено: <span class="font-semibold text-gray-300">{{ $events->total() }}</span> заходів
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @foreach($events as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>

            {{ $events->links() }}
        @endif
    </div>

</x-app-layout>
