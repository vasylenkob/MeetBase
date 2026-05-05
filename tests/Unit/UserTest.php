<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_admin_role_is_detected_correctly(): void
    {
        $user = new User(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isOrganizer());
        $this->assertFalse($user->isAttendee());
    }

    public function test_organizer_role_is_detected_correctly(): void
    {
        $user = new User(['role' => 'organizer']);
        $this->assertTrue($user->isOrganizer());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isAttendee());
    }

    public function test_attendee_role_is_detected_correctly(): void
    {
        $user = new User(['role' => 'attendee']);
        $this->assertTrue($user->isAttendee());
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isOrganizer());
    }

    public function test_user_is_not_blocked_by_default(): void
    {
        $user = new User(['is_blocked' => false]);
        $this->assertFalse($user->is_blocked);
    }

    public function test_blocked_user_flag_is_set_correctly(): void
    {
        $user = new User(['is_blocked' => true]);
        $this->assertTrue($user->is_blocked);
    }
}
