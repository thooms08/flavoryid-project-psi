<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mengganti tabel mine_ai_* dengan tabel flavory_ai_*.
 *
 * - Drop mine_ai_messages dulu (FK ke mine_ai_sessions)
 * - Drop mine_ai_sessions
 * - Buat flavory_ai_sessions
 * - Buat flavory_ai_messages
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Hapus tabel lama ──────────────────────────────────────────
        Schema::dropIfExists('mine_ai_messages');
        Schema::dropIfExists('mine_ai_sessions');

        // ── Buat tabel sesi chat FlavoryAI ────────────────────────────
        Schema::create('flavory_ai_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->foreignId('partner_id')
                  ->constrained('partners')
                  ->onDelete('cascade');
            $table->string('title', 120)->default('Obrolan Baru');
            $table->timestamps();
        });

        // ── Buat tabel pesan per sesi ─────────────────────────────────
        Schema::create('flavory_ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flavory_ai_session_id')
                  ->constrained('flavory_ai_sessions')
                  ->onDelete('cascade');
            $table->enum('role', ['user', 'assistant']);
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flavory_ai_messages');
        Schema::dropIfExists('flavory_ai_sessions');

        // Buat ulang tabel lama (untuk rollback)
        Schema::create('mine_ai_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->string('title', 100)->default('Obrolan Baru');
            $table->json('business_context')->nullable();
            $table->timestamps();
        });

        Schema::create('mine_ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mine_ai_session_id')
                  ->constrained('mine_ai_sessions')
                  ->onDelete('cascade');
            $table->enum('role', ['user', 'assistant']);
            $table->text('content');
            $table->timestamps();
        });
    }
};
