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
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('nominal_diskon', 15, 2)->default(0)->after('total_harga');
        $table->string('jenis_diskon', 5)->default('Rp')->after('nominal_diskon');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['nominal_diskon', 'jenis_diskon']);
    });
}
};
