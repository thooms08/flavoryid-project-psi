<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'uuid',
        'slug',
        'event_name',
        'description',
        'image',
        'url',
        'event_start_date',
        'event_end_date',
        'status',
        'wa_group_link',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($event) {
            $event->uuid = (string) Str::uuid();

            if (empty($event->slug)) {
                $event->slug = static::generateUniqueSlug($event->event_name);
            }
        });
    }

    /**
     * Generate slug unik. Jika sudah ada slug yang sama, tambahkan
     * 4-5 karakter acak (huruf kecil + angka) di belakang.
     */
    public static function generateUniqueSlug(string $eventName, ?int $excludeId = null): string
    {
        $base = Str::slug($eventName);
        $slug = $base;

        $query = static::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->clone()->exists()) {
            // 4 karakter acak: huruf kecil + angka
            $suffix = strtolower(Str::random(2)) . rand(10, 99);
            $slug   = $base . '-' . $suffix;
            $query  = static::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }
}
