<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kos extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','nama','slug','deskripsi','jenis','tipe_kamar',
        'harga_per_bulan','harga_per_tahun','luas_kamar',
        'alamat','kota','provinsi','kecamatan','kelurahan',
        'latitude','longitude','foto_utama','foto_tambahan',
        'fasilitas_kamar','fasilitas_bersama','peraturan',
        'nama_pemilik','telepon_pemilik','is_available','rating','total_review',
    ];

    protected $casts = [
        'foto_tambahan'   => 'array',
        'fasilitas_kamar' => 'array',
        'fasilitas_bersama' => 'array',
        'is_available'    => 'boolean',
        'harga_per_bulan' => 'integer',
        'harga_per_tahun' => 'integer',
        'latitude'        => 'float',
        'longitude'       => 'float',
        'rating'          => 'float',
    ];

    public function scopeAvailable($q)     { return $q->where('is_available', true); }
    public function scopeJenis($q, $jenis) { return $jenis ? $q->where('jenis', $jenis) : $q; }
    public function scopeKota($q, $kota)   { return $kota  ? $q->where('kota', 'like', "%{$kota}%") : $q; }
    public function scopeHargaMax($q, $max){ return $max   ? $q->where('harga_per_bulan', '<=', $max) : $q; }
    public function scopeSearch($q, $keyword) {
        return $keyword ? $q->where(fn($x) => $x
            ->where('nama','like',"%{$keyword}%")
            ->orWhere('alamat','like',"%{$keyword}%")
            ->orWhere('kota','like',"%{$keyword}%")
            ->orWhere('kecamatan','like',"%{$keyword}%")) : $q;
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_bulan, 0, ',', '.');
    }

    public function getHargaMulaiDariAttribute(): string
    {
        // Jika ada tipe kamar, ambil harga terkecil. Jika tidak, pakai harga_per_bulan.
        if ($this->relationLoaded('tipeKamar') && $this->tipeKamar->isNotEmpty()) {
            $min = $this->tipeKamar->min('harga_per_bulan');
            return 'Rp ' . number_format($min, 0, ',', '.');
        }
        return 'Rp ' . number_format($this->harga_per_bulan, 0, ',', '.');
    }
    public function getFotoUtamaUrlAttribute(): string
    {
        return $this->foto_utama
            ? asset('storage/' . $this->foto_utama)
            : 'https://placehold.co/600x400/2563eb/white?text=BaKos';
    }
    public function getLokasiLengkapAttribute(): string
    {
        return "{$this->kelurahan}, {$this->kecamatan}, {$this->kota}";
    }

    public function user()        { return $this->belongsTo(User::class); }
    public function favoritedBy() { return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); }
    public function tipeKamar()   { return $this->hasMany(KosTipeKamar::class)->orderBy('urutan')->orderBy('harga_per_bulan'); }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest(); // Tampilkan yang terbaru dulu
    }
}