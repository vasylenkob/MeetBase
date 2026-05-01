<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function show(Event $event)
    {
        if ($event->status !== 'published' || $event->isFree() || $event->isPast()) {
            abort(404);
        }

        $exists = Registration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            return redirect()->route('events.show', $event)->with('error', 'Ви вже зареєстровані.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => (int) ($event->price * 100), // в копійках
            'currency' => 'uah',
            'metadata' => [
                'event_id' => $event->id,
                'user_id'  => auth()->id(),
            ],
        ]);

        return view('payment.show', [
            'event'         => $event,
            'clientSecret'  => $intent->client_secret,
            'stripeKey'     => config('services.stripe.key'),
        ]);
    }

    public function confirm(Request $request, Event $event)
    {
        $request->validate([
            'payment_intent_id' => 'required|string|starts_with:pi_',
        ]);

        if ($event->isPast()) {
            return back()->with('error', 'Реєстрація закрита — захід вже завершився.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return back()->with('error', 'Платіж не підтверджено. Спробуйте ще раз.');
        }

        // Додаткова перевірка: PaymentIntent справді для цього заходу і цього юзера
        if (
            (int) $intent->metadata->event_id !== $event->id ||
            (int) $intent->metadata->user_id  !== auth()->id()
        ) {
            abort(403);
        }

        $registration = Registration::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => auth()->id()],
            [
                'ticket_code'    => strtoupper(Str::random(10)),
                'payment_status' => 'paid',
                'status'         => 'active',
            ]
        );

        return redirect()->route('tickets.show', $registration)
            ->with('success', 'Оплату прийнято! Ваш квиток готовий.');
    }
}
