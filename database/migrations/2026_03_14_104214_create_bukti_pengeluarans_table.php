<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_id')->constrained('pengeluarans')->onDelete('cascade');
            $table->string('path_bukti');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_pengeluarans');
    }
};