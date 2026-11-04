<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlavoryAiMessage extends Model
{
    protected $table = 'flavory_ai_messages';

    protected $fillable = ['flavory_ai_session_id', 'role', 'content'];

    /**
     * Enkripsi otomatis kolom content menggunakan APP_KEY (AES-256-CBC).
     * Laravel mengenkripsi saat simpan (create/update) dan
     * mendekripsi saat baca — controller tidak perlu diubah.
     */
    protected $casts = [
        'content' => 'encrypted',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(FlavoryAiSession::class, 'flavory_ai_session_id');
    }
}
