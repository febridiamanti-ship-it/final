<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\KosTipeKamar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class KosController extends Controller
{
    public function home()
    {
        $kosUnggulan = Kos::available()->orderByDesc('rating')->limit(8)->get();
        $kotaPopuler = Kos::available()->selectRaw('kota, COUNT(*) as total')
            ->groupBy('kota')->orderByDesc('total')->limit(6)->get();
            
        // --- TAMBAHKAN BARIS INI ---
        // Ambil 3 ulasan terbaru yang memiliki rating 4 atau 5, beserta data user-nya
        $latestReviews = \App\Models\Review::with('user')
            ->where('rating', '>=', 4)
            ->whereNotNull('komentar')
            ->latest()
            ->limit(3)
            ->get();
            
        return view('home', compact('kosUnggulan', 'kotaPopuler', 'latestReviews')); 
        // Catatan: sesuaikan nama view ('welcome' atau 'home') dengan yang kamu gunakan
    }

    public function index(Request $request)
    {
        $query = Kos::available();
        $query->search($request->q)
              ->jenis($request->jenis)
              ->kota($request->kota)
              ->hargaMax($request->harga_max);
              
        if ($request->tipe)      $query->where('tipe_kamar', $request->tipe);
        if ($request->fasilitas) foreach ($request->fasilitas as $f) $query->whereJsonContains('fasilitas_kamar', $f);

        match ($request->sort ?? 'rating') {
            'harga_asc'  => $query->orderBy('harga_per_bulan'),
            'harga_desc' => $query->orderByDesc('harga_per_bulan'),
            'terbaru'    => $query->orderByDesc('created_at'),
            default      => $query->orderByDesc('rating'),
        };

        $kosList   = $query->paginate(12)->withQueryString();

        // Pre-process map data as JSON to avoid Blade parser issues with array syntax
        $mapData = json_encode(
            Kos::available()
                ->search($request->q)
                ->jenis($request->jenis)
                ->kota($request->kota)
                ->hargaMax($request->harga_max)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->select('id','nama','latitude','longitude','harga_per_bulan','jenis','is_available','slug')
                ->get()
                ->map(fn($k) => [
                    'id'        => $k->id,
                    'nama'      => $k->nama,
                    'lat'       => (float)$k->latitude,
                    'lng'       => (float)$k->longitude,
                    'harga'     => $k->harga_format,
                    'jenis'     => $k->jenis,
                    'url'       => route('kos.show', $k),
                    'available' => (bool)$k->is_available,
                ])
                ->values()
        );

        return view('kos.index', compact('kosList', 'mapData'));
    }

    public function show(Kos $kos)
    {
        $kos->load('tipeKamar', 'reviews.user');

        $kosSekitar = Kos::available()
            ->where('id', '!=', $kos->id)
            ->where('kota', $kos->kota)
            ->limit(4)
            ->get();

        // Cek apakah user sudah favorit kos ini
        $isFavorited = auth()->check() && auth()->user()->hasFavorited($kos->id);

        return view('kos.show', compact('kos', 'kosSekitar', 'isFavorited'));
    }

    public function create()
    {
        return view('kos.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama'            => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'jenis'           => 'required|in:putra,putri,campur',
            'tipe_kamar'      => 'required|in:kos,kontrakan,apartemen',
            'harga_per_bulan' => 'required|integer|min:0',
            'harga_per_tahun' => 'nullable|integer|min:0',
            'luas_kamar'      => 'nullable|integer',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'kecamatan'       => 'nullable|string|max:100',
            'kelurahan'       => 'nullable|string|max:100',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'foto_utama'      => 'nullable|image|max:5120',
            'foto_tambahan.*' => 'nullable|image|max:5120',
            'fasilitas_kamar'   => 'nullable|array',
            'fasilitas_bersama' => 'nullable|array',
            'peraturan'       => 'nullable|string',
            'nama_pemilik'    => 'required|string|max:100',
            'telepon_pemilik' => 'required|string|max:20',
        ]);

        if ($request->hasFile('foto_utama')) {
            $v['foto_utama'] = $request->file('foto_utama')->store('kos/foto/utama', 'public');
        }

        if ($request->hasFile('foto_tambahan')) {
            $fotoTambahanPaths = [];
            foreach ($request->file('foto_tambahan') as $key => $file) {
                // Pastikan file tidak null dan benar-benar terunggah
                if ($file && $file->isValid()) {
                    $fotoTambahanPaths[$key] = $file->store('kos/foto/tambahan', 'public');
                }
            }
            $v['foto_tambahan'] = $fotoTambahanPaths;
        }

        $v['slug']         = Str::slug($v['nama']) . '-' . Str::random(6);
        $v['is_available'] = true;
        $v['rating']       = 0;
        $v['total_review'] = 0;
        $v['user_id']      = auth()->id();

        // Jika pemilik, isi nama_pemilik dari akun (Jika menggunakan fitur role)
        if (auth()->check() && method_exists(auth()->user(), 'isPemilik') && auth()->user()->isPemilik()) {
            $v['nama_pemilik']    = $v['nama_pemilik'] ?: auth()->user()->name;
            $v['telepon_pemilik'] = $v['telepon_pemilik'] ?: auth()->user()->telepon;
        }

        $kos = Kos::create($v);

        // ── Simpan Tipe Kamar ──────────────────────────────────────────────────
        $this->saveTipeKamar($kos, $request->input('tipe_kamar_list', []));

        return redirect()->route('kos.show', $kos)->with('success', 'Kos berhasil ditambahkan!');
    }

    public function edit(Kos $kos)
    {
        $this->authorizeKos($kos);
        return view('kos.edit', compact('kos'));
    }

    public function update(Request $request, Kos $kos)
    {
        $this->authorizeKos($kos);

        $v = $request->validate([
            'nama'            => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'jenis'           => 'required|in:putra,putri,campur',
            'tipe_kamar'      => 'required|in:kos,kontrakan,apartemen',
            'harga_per_bulan' => 'required|integer|min:0',
            'harga_per_tahun' => 'nullable|integer|min:0',
            'luas_kamar'      => 'nullable|integer',
            'alamat'          => 'required|string',
            'kota'            => 'required|string|max:100',
            'provinsi'        => 'required|string|max:100',
            'kecamatan'       => 'nullable|string|max:100',
            'kelurahan'       => 'nullable|string|max:100',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
            'nama_pemilik'    => 'required|string|max:100',
            'telepon_pemilik' => 'required|string|max:20',
            'peraturan'       => 'nullable|string',
            'is_available'    => 'nullable|boolean',
            'foto_utama'      => 'nullable|image|max:5120',
            'foto_tambahan.*' => 'nullable|image|max:5120',
            'fasilitas_kamar'   => 'nullable|array',
            'fasilitas_bersama' => 'nullable|array',
        ]);

        // Proses Update Foto Utama
        if ($request->hasFile('foto_utama')) {
            // Hapus file lama jika ada
            if ($kos->foto_utama) {
                Storage::disk('public')->delete($kos->foto_utama);
            }
            $v['foto_utama'] = $request->file('foto_utama')->store('kos/foto/utama', 'public');
        }

        // Proses Update Foto Tambahan
        if ($request->hasFile('foto_tambahan')) {
            // Ambil data foto lama dari DB (Penting: Model Kos harus punya $casts array)
            $fotoTambahanPaths = is_array($kos->foto_tambahan) ? $kos->foto_tambahan : [];
            
            // Looping berdasarkan Kategori (kamar, kamar_mandi, dapur, fasilitas)
            foreach ($request->file('foto_tambahan') as $key => $file) {
                if ($file && $file->isValid()) {
                    
                    // Jika di kategori tersebut sebelumnya sudah ada foto, hapus foto lamanya
                    if (isset($fotoTambahanPaths[$key])) {
                       Storage::disk('public')->delete($fotoTambahanPaths[$key]);
                    }
                    
                    // Simpan foto baru dan tetapkan pada kategori yang tepat
                    $fotoTambahanPaths[$key] = $file->store('kos/foto/tambahan', 'public');
                }
            }
            
            $v['foto_tambahan'] = $fotoTambahanPaths;
        }

        $kos->update($v);

        // ── Sync Tipe Kamar ────────────────────────────────────────────────────
        $this->saveTipeKamar($kos, $request->input('tipe_kamar_list', []));

        return redirect()->route('kos.show', $kos)->with('success', 'Kos berhasil diperbarui!');
    }

    public function destroy(Kos $kos)
    {
        $this->authorizeKos($kos);
        
        // Catatan: Karena menggunakan SoftDeletes, file fisik tidak langsung dihapus di sini
        // agar data bisa di-restore di masa depan.
        $kos->delete();
        
        return redirect()->route('kos.index')->with('success', 'Kos berhasil dihapus.');
    }

    public function apiIndex(Request $request)
    {
        $kos = Kos::available()
            ->when($request->kota, fn($q) => $q->kota($request->kota))
            ->select('id','nama','latitude','longitude','harga_per_bulan','jenis','foto_utama')
            ->get()->map(fn($k) => [
                'id'    => $k->id, 
                'nama'  => $k->nama,
                'lat'   => $k->latitude, 
                'lng'   => $k->longitude,
                'harga' => $k->harga_format, 
                'jenis' => $k->jenis,
                'foto'  => $k->foto_utama_url, 
                'url'   => route('kos.show', $k),
            ]);
            
        return response()->json($kos);
    }

    // ─── Helper: Cek kepemilikan atau admin ───
    private function authorizeKos(Kos $kos): void
    {
        $user = auth()->user();
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) return;
        
        if ($kos->user_id && $kos->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola kos ini.');
        }
    }

    // ─── Helper: Simpan/Sync Tipe Kamar ──────────────────────────────────────
    private function saveTipeKamar(Kos $kos, array $tipeList): void
    {
        // Hapus semua tipe kamar lama, lalu buat ulang (simple sync)
        $kos->tipeKamar()->delete();

        foreach ($tipeList as $urutan => $item) {
            // Skip baris kosong
            if (empty($item['nama_tipe']) || empty($item['harga_per_bulan'])) continue;

            $fasilitas = isset($item['fasilitas']) && is_array($item['fasilitas'])
                ? array_values(array_filter($item['fasilitas']))
                : [];

            KosTipeKamar::create([
                'kos_id'          => $kos->id,
                'nama_tipe'       => $item['nama_tipe'],
                'harga_per_bulan' => (int) str_replace(['.', ','], '', $item['harga_per_bulan']),
                'harga_per_tahun' => !empty($item['harga_per_tahun']) ? (int) str_replace(['.', ','], '', $item['harga_per_tahun']) : null,
                'luas_kamar'      => !empty($item['luas_kamar']) ? (int)$item['luas_kamar'] : null,
                'fasilitas'       => $fasilitas,
                'kapasitas'       => !empty($item['kapasitas']) ? (int)$item['kapasitas'] : 1,
                'keterangan'      => $item['keterangan'] ?? null,
                'urutan'          => $urutan,
            ]);
        }
    }
}