<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Модерація коментарів</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            @if($comments->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-4xl mb-3">💬</p>
                    <p>Коментарів немає</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-800">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Автор</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Захід</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Коментар</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Статус</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500 text-xs uppercase tracking-wider">Дата</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($comments as $comment)
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-200">{{ $comment->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $comment->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('events.show', $comment->event->slug) }}" target="_blank"
                                       class="text-indigo-400 hover:text-indigo-300 transition text-sm">
                                        {{ Str::limit($comment->event->title, 30) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-400 max-w-xs">
                                    {{ Str::limit($comment->body, 80) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($comment->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/15 text-emerald-400 ring-1 ring-inset ring-emerald-500/25">Опубліковано</span>
                                    @elseif($comment->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/15 text-amber-400 ring-1 ring-inset ring-amber-500/25">На модерації</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-500/15 text-gray-400 ring-1 ring-inset ring-gray-500/25">Відхилено</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">
                                    {{ $comment->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 justify-end">
                                        @if($comment->status === 'pending')
                                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="text-xs bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-lg hover:bg-emerald-500/30 transition">
                                                    Схвалити
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.comments.reject', $comment) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="text-xs bg-amber-500/20 text-amber-400 px-3 py-1.5 rounded-lg hover:bg-amber-500/30 transition">
                                                    Відхилити
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Видалити коментар?')"
                                                    class="text-xs text-red-500/60 hover:text-red-400 transition">
                                                Видалити
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-800">{{ $comments->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
