<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Perubahan pada tabel users:
 * 1. Tambah enum 'owner' ke kolom role.
 * 2. Ganti unique constraint global pada username menjadi unique composite (username, role).
 *    Artinya: username 'jaka' boleh ada di role kasir DAN di role owner secara bersamaan,
 *    tetapi tidak boleh ada dua 'jaka' dengan role yang sama.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Perluas enum role — tambah 'owner'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('kasir', 'manager', 'admin', 'owner') DEFAULT 'kasir'");

        Schema::table('users', function (Blueprint $table) {
            // 2. Hapus unique constraint global yang lama pada username
            $table->dropUnique(['username']);

            // 3. Tambah unique composite (username, role)
            //    → username harus unik dalam satu role, tapi boleh sama di role berbeda
            $table->unique(['username', 'role'], 'users_username_role_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan: hapus composite unique
            $table->dropUnique('users_username_role_unique');

            // Kembalikan unique global (hati-hati jika sudah ada duplikat username antar role)
            $table->unique('username');
        });

        // Kembalikan enum tanpa 'owner'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('kasir', 'manager', 'admin') DEFAULT 'kasir'");
    }
};
