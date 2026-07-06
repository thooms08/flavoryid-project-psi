<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mine_ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mine_ai_session_id')
                  ->constrained('mine_ai_sessions')
                  ->onDelete('cascade');
            $table->enum('role', ['user', 'assistant']);
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mine_ai_messages');
    }
};
