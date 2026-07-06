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
        Schema::table('order_details', function (Blueprint $table) {
            // Menambahkan kolom variant bertipe string
            // nullable() karena menu yang tidak punya varian akan bernilai null
            // after('menu_id') agar posisinya rapi diletakkan setelah kolom menu_id
            $table->string('variant')->nullable()->after('menu_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('variant');
        });
    }
};