<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'telepon', 'foto_profil', 'bio', 'kota',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Role Helpers ───
    public function isAdmin():   bool { return $this->role === 'admin'; }
    public function isPemilik(): bool { return $this->role === 'pemilik'; }
    public function isPencari(): bool { return $this->role === 'pencari'; }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'Admin',
            'pemilik' => 'Pemilik Kos',
            default   => 'Pencari Kos',
        };
    }

    public function getFotoProfilUrlAttribute(): string
    {
        return $this->foto_profil
            ? asset('storage/' . $this->foto_profil)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&size=128';
    }

    // ─── Relasi ───
    public function kosList()
    {
        return $this->hasMany(Kos::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Kos::class, 'favorites')->withTimestamps();
    }

    public function hasFavorited(int $kosId): bool
    {
        return $this->favorites()->where('kos_id', $kosId)->exists();
    }

    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}