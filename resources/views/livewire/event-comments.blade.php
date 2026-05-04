<?php
use App\Models\Event;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

new class extends Component {
    use WithPagination, WithoutUrlPagination;

    public Event $event;
    public string $body    = '';
    public string $success = '';

    public function postComment(): void
    {
        $this->validate(['body' => 'required|min:1|max:1000']);

        $this->event->comments()->create([
            'user_id' => auth()->id(),
            'body'    => trim($this->body),
            'status'  => 'pending',
        ]);

        $this->body    = '';
        $this->success = 'Ваш коментар надіслано на модерацію.';
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'comments' => $this->event->comments()
                ->where('status', 'approved')
                ->with('user')
                ->latest()
                ->paginate(10),

            'ownPendingComment' => auth()->check()
                ? $this->event->comments()
                    ->where('user_id', auth()->id())
                    ->where('status', 'pending')
                    ->latest()
                    ->first()
                : null,
        ];
    }
};
?>

<div>
    <h3 class="text-lg font-semibold text-white mb-4">
        💬 Коментарі
        <span class="text-sm font-normal text-gray-500 ml-1">({{ $comments->total() }})</span>
    </h3>

    {{-- Own pending comment --}}
    @if($ownPendingComment)
        <div class="mb-4 bg-amber-500/10 border border-amber-500/20 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded-full bg-amber-500/20 text-amber-400 text-xs font-bold flex items-center justify-center">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="text-sm font-medium text-gray-300">{{ auth()->user()->name }}</span>
                <span class="text-xs bg-amber-500/15 text-amber-400 ring-1 ring-inset ring-amber-500/25 px-2 py-0.5 rounded-full">на модерації</span>
            </div>
            <p class="text-gray-400 text-sm leading-relaxed">{{ $ownPendingComment->body }}</p>
        </div>
    @endif

    {{-- Approved comments --}}
    @if($comments->isEmpty() && !$ownPendingComment)
        <p class="text-gray-500 text-sm mb-4">Поки що немає коментарів. Будьте першим!</p>
    @elseif($comments->isNotEmpty())
        <div class="space-y-4 mb-4">
            @foreach($comments as $comment)
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 text-sm font-bold flex items-center justify-center shrink-0">
                        {{ mb_strtoupper(mb_substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-sm font-medium text-gray-300">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $comment->body }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        @if($comments->hasPages())
            <div class="mb-6">{{ $comments->links() }}</div>
        @endif
    @endif

    {{-- Comment form --}}
    @auth
        @if($success)
            <div class="mb-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm">
                {{ $success }}
            </div>
        @endif
        @if(!$ownPendingComment)
            <form wire:submit="postComment">
                <textarea wire:model="body" rows="3" placeholder="Напишіть коментар..."
                          class="w-full bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none"
                          maxlength="1000"></textarea>
                <x-input-error :messages="$errors->get('body')" class="mt-1" />
                <div class="mt-2 flex justify-end">
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                            class="bg-indigo-500 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-400 transition font-medium">
                        <span wire:loading.remove>Надіслати</span>
                        <span wire:loading>Надсилаємо…</span>
                    </button>
                </div>
            </form>
        @endif
    @else
        <div class="bg-gray-800/50 border border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-500 text-center">
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 transition">Увійдіть</a>,
            щоб залишити коментар
        </div>
    @endauth
</div>
