<x-app-layout>
    <div class="max-w-lg mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                <h1 class="text-xl font-bold">Оплата квитка</h1>
                <p class="text-indigo-200 mt-1">{{ $event->title }}</p>
                <div class="text-3xl font-bold mt-2">{{ number_format($event->price, 0) }} грн</div>
            </div>

            <form method="POST" action="{{ route('payment.process', $event->slug) }}" class="px-8 py-6 space-y-5">
                @csrf

                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg text-sm">
                    🔒 Це демонстраційна форма оплати. Введіть будь-які дані.
                </div>

                <div>
                    <x-input-label for="card_number" value="Номер картки" />
                    <x-text-input id="card_number" name="card_number" type="text" class="mt-1 block w-full"
                                  placeholder="1234 5678 9012 3456" maxlength="16" required />
                    <x-input-error :messages="$errors->get('card_number')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="card_name" value="Ім'я на картці" />
                    <x-text-input id="card_name" name="card_name" type="text" class="mt-1 block w-full"
                                  placeholder="IVAN PETRENKO" required />
                    <x-input-error :messages="$errors->get('card_name')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="expiry" value="Термін дії" />
                        <x-text-input id="expiry" name="expiry" type="text" class="mt-1 block w-full"
                                      placeholder="MM/YY" maxlength="5" required />
                        <x-input-error :messages="$errors->get('expiry')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="cvv" value="CVV" />
                        <x-text-input id="cvv" name="cvv" type="text" class="mt-1 block w-full"
                                      placeholder="123" maxlength="3" required />
                        <x-input-error :messages="$errors->get('cvv')" class="mt-1" />
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 transition font-semibold">
                    Оплатити {{ number_format($event->price, 0) }} грн
                </button>

                <a href="{{ route('events.show', $event->slug) }}"
                   class="block text-center text-sm text-gray-500 hover:text-gray-700 transition">
                    Скасувати
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
