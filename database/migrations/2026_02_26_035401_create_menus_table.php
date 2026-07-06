<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel partners (asumsi tabel partners sudah ada)
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->string('nama_menu');
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->default('F&B');
            $table->string('gambar')->nullable();
            $table->decimal('harga', 12, 2);
            $table->enum('tipe_menu', ['best_seller', 'utama'])->default('utama');
            $table->enum('status_menu', ['tersedia', 'habis'])->default('tersedia');
            $table->integer('stok_menu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menus');
    }
};