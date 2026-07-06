<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->integer('amount');
            $table->string('paket');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_url')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('subscriptions'); }
};