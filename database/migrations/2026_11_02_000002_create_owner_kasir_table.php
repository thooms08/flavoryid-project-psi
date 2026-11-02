<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pivot owner_kasir
 * -----------------------------------------------------------
 * Menghubungkan akun owner (role=owner) dengan akun kasir (role=kasir)
 * yang berasal dari outlet/partner yang sama.
 *
 * Ketika user daftar, sistem otomatis membuat:
 *   - 1 akun kasir  (user_id = kasir_id di sini)
 *   - 1 akun owner  (owner_id di sini)
 * Keduanya dihubungkan via tabel ini.
 *
 * Relasi: satu kasir bisa punya tepat satu owner, satu owner bisa
 *         mengelola banyak kasir (jika multioutlet di masa depan).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('owner_kasir', function (Blueprint $table) {
            $table->id();

            // Akun owner (role = 'owner')
            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('FK ke users.id dengan role=owner');

            // Akun kasir (role = 'kasir')
            $table->foreignId('kasir_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('FK ke users.id dengan role=kasir');

            $table->timestamps();

            // Satu kasir hanya boleh terhubung ke satu owner
            $table->unique('kasir_id');
            // Composite: satu owner tidak boleh terhubung dua kali ke kasir yang sama
            $table->unique(['owner_id', 'kasir_id'], 'owner_kasir_pair_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_kasir');
    }
};
