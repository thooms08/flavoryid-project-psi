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
        Schema::table('informasi_outlet', function (Blueprint $table) {
            // Menambahkan kolom logo setelah kolom nama_outlet (biar rapi di DB)
            // Tipe data string untuk menyimpan nama file gambar
            $table->string('logo')->nullable()->after('nama_outlet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasi_outlet', function (Blueprint $table) {
            // Menghapus kolom logo jika migrasi di-rollback
            $table->dropColumn('logo');
        });
    }
};