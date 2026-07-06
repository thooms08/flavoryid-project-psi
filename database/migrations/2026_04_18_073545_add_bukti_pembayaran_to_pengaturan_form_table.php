<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengaturan_form', function (Blueprint $table) {
            // Menambahkan kolom boolean dengan default true
            $table->boolean('bukti_pembayaran')->default(true)->after('diskon');
        });
    }

    public function down()
    {
        Schema::table('pengaturan_form', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }
};