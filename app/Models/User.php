<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    // Password otomatis di-hash via cast di Laravel modern
    protected function casts(): array {
        return [
            'password' => 'hashed',
        ];
    }
    public function partner() {
    return $this->hasOne(Partner::class, 'user_id');
}
}