<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Jam mulai & jam akhir event (nullable — tidak semua event perlu jam spesifik)
            $table->time('event_start_time')->nullable()->after('event_start_date');
            $table->time('event_end_time')->nullable()->after('event_end_date');
            // Lokasi event (nullable — bisa online / belum ditentukan)
            $table->string('location')->nullable()->after('event_end_time');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_start_time', 'event_end_time', 'location']);
        });
    }
};
