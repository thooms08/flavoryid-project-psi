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
        Schema::table('orders', function (Blueprint $table) {
            // 1. Hapus constraint unique bawaan sebelumnya
            // Laravel secara default menamai index unique: nama_tabel_nama_kolom_unique
            $table->dropUnique(['nomor_pesanan']);
        });

        Schema::table('orders', function (Blueprint $table) {
            // 2. Ubah tipe data menjadi integer
            // Kita gunakan change() untuk memodifikasi kolom yang sudah ada
            $table->integer('nomor_pesanan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan ke string dan unique jika di-rollback
            $table->string('nomor_pesanan')->unique()->change();
        });
    }
};