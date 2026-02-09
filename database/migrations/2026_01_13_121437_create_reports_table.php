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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke user
        $table->date('tanggal');
        $table->text('aktivitas');
        // Status: pending, approved, revision, rejected
        $table->enum('status', ['pending', 'approved', 'revision', 'rejected'])->default('pending');
        $table->text('komentar_mentor')->nullable(); // Untuk revisi/alasan tolak
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
