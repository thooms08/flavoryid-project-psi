<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom informasi peserta ke tabel participants.
     */
    public function up(): void
    {
        // Hapus kolom yang terlanjur masuk ke tabel events (jika ada)
        if (Schema::hasColumn('events', 'nama_usaha')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn(['nama_usaha', 'nama_instansi', 'info']);
            });
        }

        // Tambahkan ke tabel yang benar: participants
        Schema::table('participants', function (Blueprint $table) {
            if (!Schema::hasColumn('participants', 'nama_usaha')) {
                $table->string('nama_usaha')->nullable()->after('participant_category');
            }
            if (!Schema::hasColumn('participants', 'nama_instansi')) {
                $table->string('nama_instansi')->nullable()->after('nama_usaha');
            }
            if (!Schema::hasColumn('participants', 'info')) {
                $table->string('info')->nullable()->after('nama_instansi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['nama_usaha', 'nama_instansi', 'info']);
        });
    }
};
