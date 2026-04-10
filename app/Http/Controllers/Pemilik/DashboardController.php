<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $kosList = $user->kosList()->latest()->paginate(10);

        $stats = [
            'total'     => $user->kosList()->count(),
            'available' => $user->kosList()->where('is_available', true)->count(),
            'penuh'     => $user->kosList()->where('is_available', false)->count(),
            'favorit'   => \App\Models\Kos::where('user_id', $user->id)
                            ->withCount('favoritedBy')->get()
                            ->sum('favorited_by_count'),
        ];

        return view('pemilik.dashboard', compact('kosList', 'stats'));
    }

    public function toggleAvailable(Kos $kos)
    {
        // Pastikan hanya pemilik kos ini yang bisa ubah
        if ($kos->user_id !== auth()->id()) {
            abort(403);
        }
        $kos->update(['is_available' => !$kos->is_available]);

        return back()->with('success', 'Status kos diperbarui!');
    }
}
