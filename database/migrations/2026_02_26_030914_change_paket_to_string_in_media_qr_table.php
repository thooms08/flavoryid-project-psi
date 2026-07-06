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
        // Kita ubah dari ENUM menjadi String agar lebih fleksibel
        $table->string('paket', 50)->change(); 
    });
}

public function down(): void
{
    Schema::table('media_qr', function (Blueprint $table) {
        $table->enum('paket', ['gratis', 'eksklusif', 'super_eksklusif'])->change();
    });
}
};
