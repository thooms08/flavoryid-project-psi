<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            // Menambahkan kolom uuid, ditaruh setelah kolom id atau user_id agar rapi
            $table->uuid('uuid')->unique()->after('user_id'); 
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};