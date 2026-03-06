<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'image',
        'starts_at',
        'ends_at',
        'location',
        'address',
        'latitude',
        'longitude',
        'price',
        'capacity',
        'status',
        'is_online',
        'online_url',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'price' => 'decimal:2',
            'is_online' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Event $event) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
        });
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'registrations')
            ->withPivot('ticket_code', 'payment_status', 'status')
            ->withTimestamps();
    }

    public function isPast(): bool
    {
        return $this->ends_at->isPast();
    }

    public function isFree(): bool
    {
        return $this->price == 0;
    }

    public function spotsLeft(): int
    {
        $taken = $this->registrations()->where('status', 'active')->count();
        return max(0, $this->capacity - $taken);
    }

    public function isFull(): bool
    {
        return $this->spotsLeft() === 0;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('ends_at', '>=', now());
    }
}
