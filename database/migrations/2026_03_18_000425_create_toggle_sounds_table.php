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
        Schema::create('toggle_sounds', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel partners
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            // Status suara (default false / OFF)
            $table->boolean('is_sound_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toggle_sounds');
    }
};
