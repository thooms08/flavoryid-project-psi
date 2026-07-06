<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_form', function (Blueprint $table) {
            $table->id();
            // Asumsi tabel partners sudah ada. cascadeOnDelete() memastikan data terhapus jika partner dihapus
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            
            // Kolom toggle dengan default true (1)
            $table->boolean('nama')->default(true);
            $table->boolean('whatsapp')->default(true);
            $table->boolean('meja')->default(true);
            $table->boolean('keterangan')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_form');
    }
};