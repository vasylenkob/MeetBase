<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" wire:navigate class="shrink-0 flex items-center gap-2 font-bold text-xl text-indigo-400 hover:text-indigo-300 transition">
                    🎪 Meetbase
                </a>

                <!-- Nav Links -->
                <div class="hidden space-x-6 sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" wire:navigate>
                        Заходи
                    </x-nav-link>

                    @auth
                        @if(!auth()->user()->isAdmin())
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            Кабінет
                        </x-nav-link>
                        @endif

                        @if(auth()->user()->isOrganizer() || auth()->user()->isAdmin())
                            <x-nav-link :href="route('organizer.events')" :active="request()->routeIs('organizer.*')" wire:navigate>
                                Мої заходи
                            </x-nav-link>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" wire:navigate>
                                Адмін
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition px-3 py-2">Вхід</a>
                    <a href="{{ route('register') }}" class="text-sm bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-400 transition font-medium">Реєстрація</a>
                @else
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-gray-700 text-sm rounded-lg text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white transition">
                                <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                                @if(auth()->user()->is_blocked)
                                    <span class="bg-red-900/50 text-red-400 text-xs font-medium px-1.5 py-0.5 rounded ring-1 ring-red-500/30">Бан</span>
                                @endif
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>Профіль</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start">
                                    <x-dropdown-link>Вийти</x-dropdown-link>
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-300 hover:bg-gray-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-800 bg-gray-900">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('events.index')" wire:navigate>Заходи</x-responsive-nav-link>
            @auth
                @if(!auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('dashboard')" wire:navigate>Кабінет</x-responsive-nav-link>
                @endif
                @if(auth()->user()->isOrganizer() || auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('organizer.events')" wire:navigate>Мої заходи</x-responsive-nav-link>
                @endif
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" wire:navigate>Адмін</x-responsive-nav-link>
                @endif
            @endauth
        </div>
        <div class="pt-4 pb-1 border-t border-gray-800">
            @auth
                <div class="px-4 mb-3">
                    <div class="flex items-center gap-2 font-medium text-base text-white">
                        {{ auth()->user()->name }}
                        @if(auth()->user()->is_blocked)
                            <span class="bg-red-900/50 text-red-400 text-xs font-medium px-1.5 py-0.5 rounded ring-1 ring-red-500/30">Бан</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
                <x-responsive-nav-link :href="route('profile')" wire:navigate>Профіль</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-start">
                        <x-responsive-nav-link>Вийти</x-responsive-nav-link>
                    </button>
                </form>
            @else
                <x-responsive-nav-link :href="route('login')" wire:navigate>Вхід</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" wire:navigate>Реєстрація</x-responsive-nav-link>
            @endauth
        </div>
    </div>
</nav>
