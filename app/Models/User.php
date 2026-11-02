<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'password', 'role'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    /** Partner terkait (untuk role kasir) */
    public function partner()
    {
        return $this->hasOne(Partner::class, 'user_id');
    }

    /**
     * Jika user ini adalah KASIR → ambil relasi owner_kasir sebagai kasir
     * Dari sini bisa akses: $kasirUser->ownerKasir->owner
     */
    public function ownerKasir()
    {
        return $this->hasOne(OwnerKasir::class, 'kasir_id');
    }

    /**
     * Jika user ini adalah OWNER → ambil semua kasir yang dikelola
     * Dari sini bisa akses: $ownerUser->kasirList
     */
    public function kasirList()
    {
        return $this->hasMany(OwnerKasir::class, 'owner_id');
    }

    // ─── Helper ────────────────────────────────────────────────────────────────

    /**
     * Cek apakah username sudah dipakai dalam role tertentu.
     * Username unik per role — beda role boleh sama.
     */
    public static function usernameExistsForRole(string $username, string $role): bool
    {
        return self::where('username', $username)
                   ->where('role', $role)
                   ->exists();
    }

    /**
     * Cek apakah username sudah dipakai di role kasir ATAU owner
     * (karena keduanya dibuat bersamaan saat registrasi).
     */
    public static function usernameExistsForRegistration(string $username): bool
    {
        return self::where('username', $username)
                   ->whereIn('role', ['kasir', 'owner'])
                   ->exists();
    }
}
