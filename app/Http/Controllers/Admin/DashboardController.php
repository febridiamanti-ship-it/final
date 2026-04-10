<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kos;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_kos'     => Kos::count(),
            'total_users'   => User::count(),
            'total_pemilik' => User::where('role', 'pemilik')->count(),
            'total_pencari' => User::where('role', 'pencari')->count(),
            'kos_available' => Kos::where('is_available', true)->count(),
            'kos_penuh'     => Kos::where('is_available', false)->count(),
        ];

        $latestKos   = Kos::with('user')->latest()->limit(5)->get();
        $latestUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'latestKos', 'latestUsers'));
    }

    // ─── Kelola User ───
    public function users(Request $request)
    {
        $users = User::when($request->q, fn($q) => $q->where('name', 'like', "%{$request->q}%")
                                                      ->orWhere('email', 'like', "%{$request->q}%"))
                     ->when($request->role, fn($q) => $q->where('role', $request->role))
                     ->latest()
                     ->paginate(15)
                     ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:pencari,pemilik,admin']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role user diperbarui!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // ─── Kelola Kos ───
    public function kos(Request $request)
    {
        $kosList = Kos::with('user')
            ->when($request->q, fn($q) => $q->where('nama', 'like', "%{$request->q}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.kos', compact('kosList'));
    }

    public function deleteKos(Kos $kos)
    {
        $kos->delete();
        return back()->with('success', 'Kos berhasil dihapus.');
    }
}
