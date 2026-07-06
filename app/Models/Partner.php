<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model {
    protected $fillable = ['uuid','user_id', 'nama_manager', 'no_wa', 'email'];
    public function user() { return $this->belongsTo(User::class); }
    public function outlet() { return $this->hasOne(InformasiOutlet::class); }
    public function verifikasi() { return $this->hasOne(Verifikasi::class); }
}