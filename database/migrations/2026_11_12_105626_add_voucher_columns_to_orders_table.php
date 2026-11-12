<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom voucher setelah kolom jenis_diskon
            $table->string('kode_voucher')->nullable()->after('jenis_diskon');
            $table->decimal('nilai_voucher', 15, 2)->nullable()->after('kode_voucher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kode_voucher', 'nilai_voucher']);
        });
    }
};
