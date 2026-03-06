<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">Профіль</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sm:p-8">
            <livewire:profile.update-profile-information-form />
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sm:p-8">
            <livewire:profile.update-password-form />
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 sm:p-8">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
