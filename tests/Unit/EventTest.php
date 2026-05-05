<?php

namespace Tests\Unit;

use App\Models\Event;
use Carbon\Carbon;
use Tests\TestCase;

class EventTest extends TestCase
{
    public function test_event_is_free_when_price_is_zero(): void
    {
        $event = new Event(['price' => 0]);
        $this->assertTrue($event->isFree());
    }

    public function test_event_is_not_free_when_price_is_positive(): void
    {
        $event = new Event(['price' => 500]);
        $this->assertFalse($event->isFree());
    }

    public function test_event_is_past_when_ends_at_is_in_the_past(): void
    {
        $event = new Event();
        $event->ends_at = Carbon::yesterday();
        $this->assertTrue($event->isPast());
    }

    public function test_event_is_not_past_when_ends_at_is_in_the_future(): void
    {
        $event = new Event();
        $event->ends_at = Carbon::tomorrow();
        $this->assertFalse($event->isPast());
    }

    public function test_event_status_is_set_correctly(): void
    {
        $event = new Event(['status' => 'published']);
        $this->assertEquals('published', $event->status);
    }

    public function test_event_is_online_flag_is_set_correctly(): void
    {
        $event = new Event(['is_online' => true]);
        $this->assertTrue($event->is_online);
    }

    public function test_event_title_is_stored_correctly(): void
    {
        $event = new Event(['title' => 'Тестовий захід']);
        $this->assertEquals('Тестовий захід', $event->title);
    }
}
