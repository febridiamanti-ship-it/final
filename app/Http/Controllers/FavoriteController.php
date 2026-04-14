<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Kos $kos)
    {
        $user = auth()->user();
        $isFav = $user->favorites()->toggle($kos->id);

        $added = count($isFav['attached']) > 0;

        if ($request->wantsJson()) {
            return response()->json([
                'favorited' => $added,
                'message'   => $added ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit',
            ]);
        }

        return back()->with('success', $added ? 'Kos disimpan ke favorit!' : 'Kos dihapus dari favorit.');
    }

    public function index()
    {
        $favorites = auth()->user()->favorites()->latest('favorites.created_at')->paginate(9);
        return view('profile.favorites', compact('favorites'));
    }
}
