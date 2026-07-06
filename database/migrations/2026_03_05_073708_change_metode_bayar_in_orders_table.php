<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengubah tipe data menjadi string (varchar 255)
            // .nullable() ditambahkan agar tidak error jika pesanan belum dibayar
            $table->string('metode_bayar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Mengembalikan ke tipe enum jika migrasi di-rollback
            $table->enum('metode_bayar', ['qris', 'transfer', 'cash'])->nullable()->change();
        });
    }
};