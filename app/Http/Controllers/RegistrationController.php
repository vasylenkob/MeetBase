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

class RegistrationController extends Controller
{
    public function store(Event $event)
    {
        if (!auth()->user()->isAttendee()) {
            abort(403, 'Придбати квиток можуть лише відвідувачі.');
        }

        if ($event->status !== 'published') {
            abort(404);
        }

        if ($event->isPast()) {
            return back()->with('error', 'Реєстрація закрита — захід вже завершився.');
        }

        if ($event->isFull()) {
            return back()->with('error', 'На жаль, всі місця зайняті.');
        }

        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing?->status === 'active') {
            return back()->with('error', 'Ви вже зареєстровані на цей захід.');
        }

        if (!$event->isFree()) {
            return redirect()->route('payment.show', $event);
        }

        if ($existing) {
            $existing->update([
                'status'         => 'active',
                'ticket_code'    => strtoupper(Str::random(10)),
                'payment_status' => 'free',
            ]);
            $registration = $existing;
        } else {
            $registration = Registration::create([
                'event_id'       => $event->id,
                'user_id'        => auth()->id(),
                'ticket_code'    => strtoupper(Str::random(10)),
                'payment_status' => 'free',
                'status'         => 'active',
            ]);
        }

        return redirect()->route('tickets.show', $registration)
            ->with('success', 'Реєстрацію підтверджено! Ваш квиток готовий.');
    }

    public function cancel(Registration $registration)
    {
        if ($registration->user_id !== auth()->id()) {
            abort(403);
        }

        $registration->update(['status' => 'cancelled']);

        return redirect()->route('dashboard')->with('success', 'Реєстрацію скасовано.');
    }

    public function ticket(Registration $registration)
    {
        if ($registration->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $registration->load('event.category');

        $qrCode = $this->generateQr($registration->ticket_code);

        return view('tickets.show', compact('registration', 'qrCode'));
    }

    private function generateQr(string $data): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        return $writer->writeString($data);
    }
}
