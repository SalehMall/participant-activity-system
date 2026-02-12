<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending','approved','revision','rejected','permit','alpha') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending','approved','revision','rejected') DEFAULT 'pending'");
    }
};
