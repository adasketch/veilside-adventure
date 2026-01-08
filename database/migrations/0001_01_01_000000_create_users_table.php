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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama lengkap
        $table->string('username')->unique(); // Untuk login (adminveilside)
        $table->string('email')->unique()->nullable(); // Opsional
        $table->string('password'); // Password terenkripsi
        $table->enum('role', ['admin', 'user'])->default('user'); // Pembeda hak akses
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
