<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'kos_id',
        'user_id',
        'rating',
        'komentar',
    ];

    // Relasi ke tabel Kos
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- MAGIC LARAVEL: Otomatisasi Update Rating Kos ---
    protected static function booted()
    {
        // Berjalan setiap kali review baru disimpan atau diperbarui
        static::saved(function ($review) {
            $review->updateKosRating();
        });

        // Berjalan setiap kali review dihapus
        static::deleted(function ($review) {
            $review->updateKosRating();
        });
    }

    // Fungsi untuk menghitung dan menyimpan rata-rata ke tabel kos
    private function updateKosRating()
    {
        $kos = $this->kos;
        $kos->update([
            // Hitung rata-rata rating dari seluruh review milik kos ini
            'rating' => $kos->reviews()->avg('rating') ?? 0,
            // Hitung total jumlah review
            'total_review' => $kos->reviews()->count(),
        ]);
    }
}