<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        return view('payment.show', compact('event'));
    }

    public function process(Request $request, Event $event)
    {
        if ($event->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Реєстрація закрита — захід вже завершився.');
        }

        $request->validate([
            'card_number' => 'required|digits:16',
            'card_name'   => 'required|string',
            'expiry'      => 'required|string',
            'cvv'         => 'required|digits:3',
        ]);

        // Mock: завжди успішна оплата
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
