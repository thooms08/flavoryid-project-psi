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
            // Mengubah kolom maps menjadi nullable
            // Pastikan tipe datanya sesuai (string atau text)
            $table->text('maps')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasi_outlet', function (Blueprint $table) {
            // Mengembalikan kolom maps menjadi tidak nullable (wajib diisi)
            $table->text('maps')->nullable(false)->change();
        });
    }
};