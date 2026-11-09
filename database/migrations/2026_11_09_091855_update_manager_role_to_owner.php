<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update semua user dengan role 'manager' menjadi 'owner'
     * karena aplikasi tidak lagi menggunakan role 'manager'.
     */
    public function up(): void
    {
        // Update semua user dengan role 'manager' menjadi 'owner'
        DB::table('users')
            ->where('role', 'manager')
            ->update(['role' => 'owner']);
    }

    /**
     * Reverse the migrations.
     * 
     * Rollback: kembalikan role 'owner' yang sebelumnya 'manager'
     * menjadi 'manager' lagi.
     */
    public function down(): void
    {
        // Kembalikan role 'owner' menjadi 'manager'
        // Catatan: ini akan mengubah SEMUA owner menjadi manager,
        // termasuk yang dibuat setelah migration ini.
        // Gunakan dengan hati-hati.
        DB::table('users')
            ->where('role', 'owner')
            ->update(['role' => 'manager']);
    }
};
