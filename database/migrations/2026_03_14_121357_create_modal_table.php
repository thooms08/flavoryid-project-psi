<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modal', function (Blueprint $table) {
            $table->id();
            // Asumsi tabel partners sudah ada
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modal');
    }
};