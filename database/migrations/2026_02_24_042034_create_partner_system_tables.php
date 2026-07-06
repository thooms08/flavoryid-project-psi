<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Table Partners
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_manager');
            $table->string('no_wa');
            $table->string('email');
            $table->timestamps();
        });

        // 2. Table Informasi Outlet
        Schema::create('informasi_outlet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->enum('jenis_outlet', ['cafe', 'restoran', 'lainnya']);
            $table->string('jenis_lainnya')->nullable();
            $table->string('nama_outlet');
            $table->string('foto_outlet');
            $table->string('page_name')->unique();
            $table->text('deskripsi');
            $table->text('alamat_lengkap');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('maps');
            $table->timestamps();
        });

        // 3. Table Media QR
        Schema::create('media_qr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('informasi_id')->constrained('informasi_outlet')->onDelete('cascade');
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('x')->nullable();
            $table->enum('qr_code_design', ['original', 'Blackboard', 'FreshBite', 'MonoBite', 'EasyBite']);
            $table->enum('paket', ['gratis', 'eksklusif', 'super_eksklusif']);
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });

        // 4. Table Verifikasi
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->string('kode_verifikasi', 6)->unique();
            $table->boolean('konfirmasi')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('verifikasi');
        Schema::dropIfExists('media_qr');
        Schema::dropIfExists('informasi_outlet');
        Schema::dropIfExists('partners');
    }
};