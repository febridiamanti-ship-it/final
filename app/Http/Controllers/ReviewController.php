<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Kos $kos)
    {
        // Validasi input dari user
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        // Cek apakah user sudah pernah mereview kos ini sebelumnya
        $existingReview = $kos->reviews()->where('user_id', auth()->id())->first();
        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk kos ini.');
        }

        // Simpan review ke database
        $kos->reviews()->create([
            'user_id'  => auth()->id(),
            'rating'   => $request->rating,
            'komentar' => $request->komentar,
        ]);

        // Berkat "Model Event" yang kita buat sebelumnya, rating di tabel Kos akan otomatis terupdate!

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan.');
    }
}