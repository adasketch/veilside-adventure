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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // Menyimpan ID user jika login, nullable jika tamu
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->string('name');
        $table->string('whatsapp');
        $table->date('start_date');
        $table->integer('days');
        $table->integer('total_price');
        $table->string('payment_method');
        $table->json('items'); // Menyimpan detail barang (nama, qty, harga) dalam JSON
        $table->enum('status', ['pending', 'lunas', 'selesai', 'batal'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
