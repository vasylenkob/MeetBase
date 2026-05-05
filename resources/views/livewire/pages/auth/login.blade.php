<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="text-2xl font-bold text-white mb-1">Вхід</h2>
    <p class="text-sm text-gray-500 mb-6">З поверненням до Meetbase</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Пароль" />
            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox"
                       class="rounded border-gray-600 bg-gray-800 text-indigo-500 shadow-sm focus:ring-indigo-500 focus:ring-offset-gray-900"
                       name="remember">
                <span class="text-sm text-gray-400">Запам'ятати мене</span>
            </label>
        </div>

        <div class="flex items-center justify-end pt-1">
            <x-primary-button>
                Увійти
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
        <span class="text-sm text-gray-500">Немає акаунту?</span>
        <a href="{{ route('register') }}" wire:navigate class="ms-1 text-sm text-indigo-400 hover:text-indigo-300 transition">
            Зареєструватись
        </a>
    </div>
</div>
