<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('events')->where('status', 'draft')->update(['status' => 'pending']);
    }

    public function down(): void
    {
        DB::table('events')->where('status', 'pending')->update(['status' => 'draft']);
    }
};
