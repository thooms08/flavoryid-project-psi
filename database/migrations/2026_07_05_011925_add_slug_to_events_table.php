<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Event;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Tambahkan kolom slug setelah uuid, nullable dulu agar bisa diisi via seeder
            $table->string('slug')->nullable()->unique()->after('uuid');
        });

        // Backfill slug untuk event yang sudah ada
        Event::all()->each(function ($event) {
            $base = Str::slug($event->event_name);
            $slug = $base;

            // Pastikan slug unik — jika bentrok tambah suffix acak
            $suffix = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $base . '-' . substr(Str::random(4), 0, 4);
                $suffix++;
            }

            $event->slug = $slug;
            $event->save();
        });

        // Setelah backfill, jadikan NOT NULL
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
