<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->string('satuan');
            
            // Range Warna Stok (Decimal)
            $table->decimal('min_hijau', 10, 2)->nullable();
            $table->decimal('max_hijau', 10, 2)->nullable();
            $table->decimal('min_kuning', 10, 2)->nullable();
            $table->decimal('max_kuning', 10, 2)->nullable();
            $table->decimal('min_merah', 10, 2)->nullable();
            $table->decimal('max_merah', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_barang');
    }
};