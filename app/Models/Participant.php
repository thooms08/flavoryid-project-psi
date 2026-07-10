<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    protected $fillable = [
        'uuid',
        'event_id',
        'name',
        'wa',
        'participant_category',
        'nama_usaha',
        'nama_instansi',
        'info',
        'certificat',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    protected static function booted()
    {
        static::creating(function ($participant) {
            $participant->uuid = (string) Str::uuid();
        });
    }
}
