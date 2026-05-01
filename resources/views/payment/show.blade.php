<x-app-layout>
    <div class="max-w-lg mx-auto px-4 py-12">

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-6 text-white">
                <p class="text-indigo-200 text-sm font-medium mb-1">{{ $event->category?->name }}</p>
                <h1 class="text-xl font-bold">{{ $event->title }}</h1>
                <div class="text-3xl font-bold mt-3">{{ number_format($event->price, 0) }} грн</div>
            </div>

            <div class="px-8 py-6 space-y-5">

                {{-- Error from server --}}
                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Stripe card element --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Дані картки</label>
                    <div id="card-element"
                         class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 min-h-[44px]"></div>
                    <p id="card-error" class="mt-2 text-sm text-red-400 hidden"></p>
                </div>

                {{-- Test cards hint --}}
                <div class="bg-gray-800/60 border border-gray-700 rounded-xl px-4 py-3 text-xs text-gray-500 space-y-1">
                    <p class="font-semibold text-gray-400 mb-1">🧪 Тестові картки:</p>
                    <p><span class="font-mono text-gray-300 select-all">4242 4242 4242 4242</span> — успішна оплата</p>
                    <p><span class="font-mono text-gray-300 select-all">4000 0000 0000 0002</span> — відмова банку</p>
                    <p class="text-gray-600">Термін дії: будь-яка майбутня дата &nbsp;·&nbsp; CVV: будь-які 3 цифри</p>
                </div>

                {{-- Pay button --}}
                <button id="pay-btn"
                        class="w-full bg-indigo-500 text-white py-3 rounded-xl hover:bg-indigo-400 transition font-semibold flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="pay-label">Оплатити {{ number_format($event->price, 0) }} грн</span>
                    <svg id="pay-spinner" class="hidden w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                </button>

                {{-- Hidden confirm form --}}
                <form id="confirm-form" method="POST" action="{{ route('payment.confirm', $event->slug) }}" class="hidden">
                    @csrf
                    <input type="hidden" name="payment_intent_id" id="payment-intent-id">
                </form>

                <a href="{{ route('events.show', $event->slug) }}"
                   class="block text-center text-sm text-gray-600 hover:text-gray-400 transition">
                    Скасувати
                </a>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');

        const elements = stripe.elements({
            appearance: {
                theme: 'night',
                variables: {
                    colorPrimary: '#6366f1',
                    colorBackground: '#1f2937',
                    colorText: '#e5e7eb',
                    colorDanger: '#f87171',
                    fontFamily: 'ui-sans-serif, system-ui, sans-serif',
                    borderRadius: '8px',
                },
            },
        });

        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const payBtn    = document.getElementById('pay-btn');
        const payLabel  = document.getElementById('pay-label');
        const spinner   = document.getElementById('pay-spinner');
        const cardError = document.getElementById('card-error');

        function setLoading(on) {
            payBtn.disabled = on;
            payLabel.textContent = on ? 'Обробляємо...' : 'Оплатити {{ number_format($event->price, 0) }} грн';
            spinner.classList.toggle('hidden', !on);
        }

        function showError(msg) {
            cardError.textContent = msg;
            cardError.classList.remove('hidden');
            setLoading(false);
        }

        payBtn.addEventListener('click', async () => {
            setLoading(true);
            cardError.classList.add('hidden');

            const { error, paymentIntent } = await stripe.confirmCardPayment(
                '{{ $clientSecret }}',
                { payment_method: { card: cardElement } }
            );

            if (error) {
                showError(error.message);
                return;
            }

            if (paymentIntent.status === 'succeeded') {
                document.getElementById('payment-intent-id').value = paymentIntent.id;
                document.getElementById('confirm-form').submit();
            } else {
                showError('Щось пішло не так. Спробуйте ще раз.');
                setLoading(false);
            }
        });
    </script>
</x-app-layout>
