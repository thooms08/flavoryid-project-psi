<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('verifikasi_codes');
    }

    public function down(): void
    {
        Schema::create('verifikasi_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('code', 6);
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
    }
};
