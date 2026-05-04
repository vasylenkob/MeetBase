<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'         => User::count(),
            'organizers'    => User::where('role', 'organizer')->count(),
            'events'        => Event::count(),
            'pending'       => Event::where('status', 'pending')->count(),
            'published'     => Event::where('status', 'published')->count(),
            'registrations'    => Registration::where('status', 'active')->count(),
            'comments_pending' => Comment::where('status', 'pending')->count(),
        ];

        $latestEvents = Event::with(['organizer', 'category'])
            ->orderByDesc('starts_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestEvents'));
    }
}
