@props(['event'])

@php $isPast = $event->isPast(); @endphp

<a href="{{ route('events.show', $event->slug) }}"
   class="flex flex-col bg-gray-900 rounded-2xl border border-gray-800 hover:border-gray-700 hover:shadow-2xl hover:shadow-black/40 transition-all duration-200 overflow-hidden group {{ $isPast ? 'opacity-50' : '' }}">

    {{-- Image --}}
    <div class="relative overflow-hidden h-44 shrink-0">
        @if($event->image)
            <img src="{{ Storage::disk('public')->url($event->image) }}" alt="{{ $event->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300 {{ $isPast ? 'grayscale' : '' }}">
        @else
            <div class="w-full h-full bg-gradient-to-br from-indigo-900/60 to-purple-900/60 flex items-center justify-center text-5xl {{ $isPast ? 'grayscale' : '' }}">
                {{ $event->category?->icon ?? '🎪' }}
            </div>
        @endif

        @if($isPast)
            <div class="absolute inset-0 bg-gray-950/50 flex items-end justify-end p-3">
                <span class="bg-gray-900/90 text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full border border-gray-700">
                    Завершено
                </span>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-5 flex flex-col flex-1">
        {{-- Badges --}}
        <div class="flex flex-wrap items-center gap-1.5 mb-3">
            @if($event->category)
                <span class="text-xs font-medium bg-indigo-500/15 text-indigo-300 px-2.5 py-1 rounded-full ring-1 ring-inset ring-indigo-500/25">
                    {{ $event->category->name }}
                </span>
            @endif
            @if($event->isFree())
                <span class="text-xs font-medium bg-emerald-500/15 text-emerald-400 px-2.5 py-1 rounded-full ring-1 ring-inset ring-emerald-500/25">Безкоштовно</span>
            @else
                <span class="text-xs font-medium bg-amber-500/15 text-amber-400 px-2.5 py-1 rounded-full ring-1 ring-inset ring-amber-500/25">
                    {{ number_format($event->price, 0) }} грн
                </span>
            @endif
            @if($event->is_online)
                <span class="text-xs font-medium bg-sky-500/15 text-sky-400 px-2.5 py-1 rounded-full ring-1 ring-inset ring-sky-500/25">Онлайн</span>
            @endif
        </div>

        {{-- Title --}}
        <h3 class="font-semibold text-white text-base leading-snug mb-3 group-hover:text-indigo-400 transition line-clamp-2 flex-1">
            {{ $event->title }}
        </h3>

        {{-- Meta --}}
        <div class="space-y-1.5 text-sm text-gray-500 mt-auto">
            <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $event->starts_at->format('d.m.Y, H:i') }}</span>
            </div>
            <div class="flex items-center gap-2">
                @if($event->is_online)
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Онлайн</span>
                @else
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate">{{ $event->location }}</span>
                @endif
            </div>
            @if(!$isPast)
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $event->spotsLeft() }} місць залишилось</span>
                </div>
            @endif
        </div>
    </div>
</a>
