<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ubah kolom `content` di flavory_ai_messages dari TEXT ke MEDIUMTEXT.
 *
 * Enkripsi AES-256-CBC (Laravel Crypt) menambah overhead ~30-40% ukuran
 * dibanding plaintext. TEXT MySQL hanya menampung 65.535 bytes, sedangkan
 * MEDIUMTEXT menampung 16 MB — cukup untuk pesan AI sepanjang apapun.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flavory_ai_messages', function (Blueprint $table) {
            $table->mediumText('content')->change();
        });
    }

    public function down(): void
    {
        Schema::table('flavory_ai_messages', function (Blueprint $table) {
            $table->text('content')->change();
        });
    }
};
