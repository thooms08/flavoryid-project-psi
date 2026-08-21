<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kode voucher yang dipakai pelanggan (nullable = tidak pakai voucher)
            $table->string('kode_voucher', 6)->nullable()->after('jenis_diskon');
            // Nilai potongan voucher yang sudah dihitung (dalam Rp)
            $table->decimal('nilai_voucher', 15, 2)->nullable()->after('kode_voucher');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kode_voucher', 'nilai_voucher']);
        });
    }
};
