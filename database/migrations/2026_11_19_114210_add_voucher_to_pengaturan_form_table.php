<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_form', function (Blueprint $table) {
            $table->boolean('voucher')->default(false)->after('bukti_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_form', function (Blueprint $table) {
            $table->dropColumn('voucher');
        });
    }
};
