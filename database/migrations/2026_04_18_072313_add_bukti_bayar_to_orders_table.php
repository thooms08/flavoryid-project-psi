<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom bukti_bayar posisinya setelah kolom kembalian
            $table->string('bukti_bayar')->nullable()->after('kembalian');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bukti_bayar');
        });
    }
};