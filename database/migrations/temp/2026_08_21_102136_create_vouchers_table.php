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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('kode_voucher', 6);
            $table->enum('jenis_potongan', ['%', 'Rp'])->default('Rp');
            $table->decimal('nominal_potongan', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Kode voucher unik per partner (bukan global unique)
            $table->unique(['partner_id', 'kode_voucher']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
