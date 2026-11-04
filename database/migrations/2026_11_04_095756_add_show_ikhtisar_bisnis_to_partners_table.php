<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom show_ikhtisar_bisnis ke tabel partners.
 *
 * Kolom ini dikontrol oleh owner/manager lewat toggle di halaman
 * Ikhtisar Bisnis. Ketika false, menu "Ikhtisar Bisnis" disembunyikan
 * dari dropdown "Opsi Lainnya" di halaman kasir.
 *
 * Default: true (menu tampil seperti biasa).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->boolean('show_ikhtisar_bisnis')
                  ->default(true)
                  ->after('email')
                  ->comment('Kontrol visibilitas menu Ikhtisar Bisnis di sisi kasir');
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('show_ikhtisar_bisnis');
        });
    }
};
