<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pengaturan_form', function (Blueprint $table) {
            $table->boolean('diskon')->default(true)->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('pengaturan_form', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });
    }
};
