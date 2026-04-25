<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KosTipeKamar extends Model
{
    use HasFactory;

    protected $table = 'kos_tipe_kamar';

    protected $fillable = [
        'kos_id',
        'nama_tipe',
        'harga_per_bulan',
        'harga_per_tahun',
        'luas_kamar',
        'fasilitas',
        'kapasitas',
        'keterangan',
        'urutan',
    ];

    protected $casts = [
        'fasilitas'       => 'array',
        'harga_per_bulan' => 'integer',
        'harga_per_tahun' => 'integer',
        'luas_kamar'      => 'integer',
        'kapasitas'       => 'integer',
        'urutan'          => 'integer',
    ];

    // ── Relationships ─────────────────────────────────
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    // ── Accessors ─────────────────────────────────────
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_bulan, 0, ',', '.');
    }

    public function getHargaTahunanFormatAttribute(): ?string
    {
        if (!$this->harga_per_tahun) return null;
        return 'Rp ' . number_format($this->harga_per_tahun, 0, ',', '.');
    }
}
