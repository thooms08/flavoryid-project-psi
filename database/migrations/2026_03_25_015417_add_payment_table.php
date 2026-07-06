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
        Schema::table('media_qr', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('paket');
            $table->string('payment_url')->nullable()->after('invoice_number');
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending')->after('payment_url');
            // Hapus kolom bukti_pembayaran jika sudah tidak terpakai
            $table->dropColumn('bukti_pembayaran'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_qrs', function (Blueprint $table) {
            //
        });
    }
};
