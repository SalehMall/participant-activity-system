<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // 1. Ubah enum role untuk menambah super_admin
        $table->enum('role', ['super_admin', 'mentor', 'intern'])->default('intern')->change();
        
        // 2. Tambahkan mentor_id untuk relasi (Intern punya Mentor)
        $table->foreignId('mentor_id')->nullable()->constrained('users')->onDelete('set null')->after('role');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
