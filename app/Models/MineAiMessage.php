<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MineAiMessage extends Model
{
    protected $table = 'mine_ai_messages';

    protected $fillable = [
        'mine_ai_session_id', 'role', 'content',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(MineAiSession::class, 'mine_ai_session_id');
    }
}
