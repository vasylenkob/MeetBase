<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isOrganizer()) {
            $events = Event::where('user_id', $user->id)
                ->withCount(['registrations' => fn($q) => $q->where('status', 'active')])
                ->orderByDesc('starts_at')
                ->take(5)
                ->get();

            return view('dashboard.organizer', compact('events'));
        }

        // Attendee
        $registrations = Registration::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('event.category')
            ->get();

        $upcomingRegistrations = $registrations
            ->filter(fn($r) => !$r->event->isPast())
            ->sortBy(fn($r) => $r->event->starts_at);

        $pastRegistrations = $registrations
            ->filter(fn($r) => $r->event->isPast())
            ->sortByDesc(fn($r) => $r->event->starts_at);

        return view('dashboard.attendee', compact('upcomingRegistrations', 'pastRegistrations'));
    }
}
