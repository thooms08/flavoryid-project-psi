<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Tambah kolom uuid (nullable dulu agar tidak error jika sudah ada data)
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        // 2. Isi data UUID untuk record yang sudah ada (Opsional jika tabel sudah ada isinya)
        $orders = DB::table('orders')->whereNull('uuid')->get();
        foreach ($orders as $order) {
            DB::table('orders')->where('id', $order->id)->update(['uuid' => (string) Str::uuid()]);
        }
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};