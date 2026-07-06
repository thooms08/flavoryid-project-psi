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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
        $table->string('nomor_pesanan')->unique();
        $table->string('nama_pelanggan')->nullable();
        $table->string('no_whatsapp')->nullable();
        $table->string('no_meja')->nullable();
        $table->text('keterangan')->nullable();
        $table->decimal('total_harga', 15, 2);
        $table->decimal('uang_bayar', 15, 2)->nullable();
        $table->enum('metode_bayar', ['qris', 'transfer', 'cash'])->nullable();
        $table->decimal('kembalian', 15, 2)->nullable();
        $table->enum('status', ['pending', 'success', 'fail'])->default('pending');
        $table->timestamps();

        $table->index('partner_id');
        $table->index('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
