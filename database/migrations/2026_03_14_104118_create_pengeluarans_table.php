<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->string('kategori')->default('operasional usaha'); // operasional usaha, operasional karyawan, lainnya
            $table->string('keterangan')->nullable();
            $table->string('item');
            $table->decimal('harga', 15, 2);
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};