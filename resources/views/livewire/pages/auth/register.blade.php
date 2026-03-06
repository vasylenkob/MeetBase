<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'attendee';

    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:organizer,attendee'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="text-2xl font-bold text-white mb-1">Реєстрація</h2>
    <p class="text-sm text-gray-500 mb-6">Приєднуйтесь до Meetbase</p>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <x-input-label for="name" value="Ім'я" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div>
            <x-input-label value="Я реєструюсь як" />
            <div class="mt-2 grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model="role" value="attendee" class="sr-only peer">
                    <div class="border-2 rounded-xl p-4 text-center transition
                                border-gray-700 bg-gray-800/50
                                peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10
                                hover:border-gray-600">
                        <div class="text-2xl mb-1">🎟️</div>
                        <div class="font-medium text-gray-200 text-sm">Відвідувач</div>
                        <div class="text-xs text-gray-500 mt-0.5">Реєстрація на заходи</div>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model="role" value="organizer" class="sr-only peer">
                    <div class="border-2 rounded-xl p-4 text-center transition
                                border-gray-700 bg-gray-800/50
                                peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10
                                hover:border-gray-600">
                        <div class="text-2xl mb-1">🎪</div>
                        <div class="font-medium text-gray-200 text-sm">Організатор</div>
                        <div class="text-xs text-gray-500 mt-0.5">Створення заходів</div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Пароль" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                          type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Підтвердження пароля" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end pt-1">
            <x-primary-button>
                Зареєструватись
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
        <span class="text-sm text-gray-500">Вже є акаунт?</span>
        <a href="{{ route('login') }}" wire:navigate class="ms-1 text-sm text-indigo-400 hover:text-indigo-300 transition">
            Увійти
        </a>
    </div>
</div>
