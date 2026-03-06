<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 'cancelled' is being removed from the system entirely.
        // Existing cancelled events are deleted (SQLite CHECK constraint
        // prevents converting them to 'pending').
        DB::table('events')->where('status', 'cancelled')->delete();
    }

    public function down(): void
    {
        // Cannot restore deleted events.
    }
};
