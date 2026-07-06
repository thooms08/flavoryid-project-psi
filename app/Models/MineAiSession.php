<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MineAiSession extends Model
{
    protected $table = 'mine_ai_sessions';

    protected $fillable = [
        'uuid', 'partner_id', 'title', 'business_context',
    ];

    protected $casts = [
        'business_context' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MineAiMessage::class, 'mine_ai_session_id')
                    ->orderBy('created_at', 'asc');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
