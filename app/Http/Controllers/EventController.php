<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // Публічний список подій
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer'])
            ->published()
            ->orderByDesc('starts_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'free') {
                $query->where('price', 0);
            } elseif ($request->type === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        $events = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    // Публічна сторінка одного заходу
    public function show(Event $event)
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $isOwnOrganizer = auth()->check() && $event->user_id === auth()->id();

        if ($event->status !== 'published' && !$isAdmin && !$isOwnOrganizer) {
            abort(404);
        }

        $event->load(['category', 'organizer', 'registrations']);

        $registration = null;
        if (auth()->check()) {
            $registration = $event->registrations()
                ->where('user_id', auth()->id())
                ->where('status', 'active')
                ->first();
        }

        return view('events.show', compact('event', 'registration'));
    }

    // --- Кабінет організатора ---

    public function myEvents()
    {
        $events = Event::where('user_id', auth()->id())
            ->with('category')
            ->withCount(['registrations' => fn($q) => $q->where('status', 'active')])
            ->orderByRaw('CASE WHEN ends_at >= ? THEN 0 ELSE 1 END', [now()])
            ->orderBy('starts_at')
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $isOnline = $request->boolean('is_online');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'starts_at'   => 'required|date|after:now',
            'ends_at'     => 'required|date|after:starts_at',
            'location'    => $isOnline ? 'nullable|string|max:255' : 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'is_online'   => 'boolean',
            'online_url'  => 'nullable|url|max:255',
            'price'       => 'required|numeric|min:0',
            'capacity'    => 'required|integer|min:1',
            'image'       => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ], [
            'image.mimes'    => 'Допустимі формати: JPG, PNG, GIF, WebP.',
            'image.max'      => 'Розмір фото не може перевищувати 5 МБ.',
            'online_url.url' => 'Введіть коректне посилання (https://...).',
        ]);

        $data['is_online'] = $isOnline;
        if ($isOnline) {
            $data['location']  = $data['location'] ?: 'Онлайн';
            $data['latitude']  = null;
            $data['longitude'] = null;
        }

        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        $data['status'] = 'pending';

        // Окремо обробляємо файл, щоб UploadedFile не потрапив у create()
        unset($data['image']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            if ($path) {
                $data['image'] = $path;
            }
        }

        $event = Event::create($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Захід надіслано на розгляд адміністратору.');
    }

    public function editEvent(Event $event)
    {
        $this->authorizeEvent($event);
        $categories = Category::all();
        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $isOnline = $request->boolean('is_online');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'starts_at'   => 'required|date',
            'ends_at'     => 'required|date|after:starts_at',
            'location'    => $isOnline ? 'nullable|string|max:255' : 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'is_online'   => 'boolean',
            'online_url'  => 'nullable|url|max:255',
            'price'       => 'required|numeric|min:0',
            'capacity'    => 'required|integer|min:1',
            'image'       => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ], [
            'image.mimes'    => 'Допустимі формати: JPG, PNG, GIF, WebP.',
            'image.max'      => 'Розмір фото не може перевищувати 5 МБ.',
            'online_url.url' => 'Введіть коректне посилання (https://...).',
        ]);

        $data['is_online'] = $isOnline;
        if ($isOnline) {
            $data['location']  = $data['location'] ?: 'Онлайн';
            $data['latitude']  = null;
            $data['longitude'] = null;
        }

        unset($data['image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            if ($path) {
                // Видаляємо старе фото зі сторейджу
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                $data['image'] = $path;
            }
        }

        $data['status'] = 'pending';

        $event->update($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Захід оновлено і надіслано на повторний розгляд адміністратору.');
    }

    public function destroyEvent(Event $event)
    {
        $this->authorizeEvent($event);
        $event->delete();

        return redirect()->route('organizer.events')->with('success', 'Захід видалено.');
    }

    public function showOrganizerEvent(Event $event)
    {
        $this->authorizeEvent($event);
        $event->load(['category', 'registrations.user']);
        return view('organizer.events.show', compact('event'));
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}
