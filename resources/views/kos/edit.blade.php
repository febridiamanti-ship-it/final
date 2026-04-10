@extends('layouts.app')
@section('title', 'Edit Kos – BaKos')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <a href="{{ route('kos.show', $kos) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-500 transition mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke detail kos
        </a>
        <h1 class="font-display text-3xl font-bold text-gray-900">Edit Kos</h1>
        <p class="text-gray-500 mt-1 text-sm">Perbarui informasi kos Anda.</p>
    </div>

    {{-- Form Utama (Update) --}}
    <form id="form-update" method="POST" action="{{ route('kos.update', $kos) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Info Dasar --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Informasi Dasar</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kos <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $kos->nama) }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 @error('nama') border-red-400 @enderror">
                @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="4" required
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none @error('deskripsi') border-red-400 @enderror">{{ old('deskripsi', $kos->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kos</label>
                    <select name="jenis" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        @foreach(['putra'=>'Kos Putra','putri'=>'Kos Putri','campur'=>'Kos Campur'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('jenis',$kos->jenis)===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Hunian</label>
                    <select name="tipe_kamar" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        @foreach(['kos'=>'Kos','kontrakan'=>'Kontrakan','apartemen'=>'Apartemen'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('tipe_kamar',$kos->tipe_kamar)===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Luas Kamar (m²)</label>
                    <input type="number" name="luas_kamar" value="{{ old('luas_kamar', $kos->luas_kamar) }}" min="1"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga / Bulan (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="harga_per_bulan" value="{{ old('harga_per_bulan', $kos->harga_per_bulan) }}" required min="0"
                               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga / Tahun (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="harga_per_tahun" value="{{ old('harga_per_tahun', $kos->harga_per_tahun) }}" min="0"
                               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Peraturan Kos</label>
                <textarea name="peraturan" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old('peraturan', $kos->peraturan) }}</textarea>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Lokasi</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="alamat" value="{{ old('alamat', $kos->alamat) }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota <span class="text-red-500">*</span></label>
                    <input type="text" name="kota" value="{{ old('kota', $kos->kota) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $kos->provinsi) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $kos->kecamatan) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelurahan</label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $kos->kelurahan) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>
        </div>

        {{-- Fasilitas (Perbaikan) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
            <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Fasilitas</h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Fasilitas Dalam Kamar</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach(['AC','Kasur','Lemari','Meja Belajar','WiFi','Kamar Mandi Dalam','TV','Kulkas'] as $fas)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50">
                        <input type="checkbox" name="fasilitas_kamar[]" value="{{ $fas }}" 
                               {{ in_array($fas, old('fasilitas_kamar', $kos->fasilitas_kamar ?? [])) ? 'checked' : '' }}
                               class="text-brand-600 focus:ring-brand-500 w-4 h-4 rounded border-gray-300">
                        <span class="text-sm font-medium text-gray-700">{{ $fas }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Fasilitas Bersama</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach(['Dapur','Parkir Motor','Parkir Mobil','Laundry','CCTV','Security 24 jam','Rooftop'] as $fas)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50">
                        <input type="checkbox" name="fasilitas_bersama[]" value="{{ $fas }}" 
                               {{ in_array($fas, old('fasilitas_bersama', $kos->fasilitas_bersama ?? [])) ? 'checked' : '' }}
                               class="text-brand-600 focus:ring-brand-500 w-4 h-4 rounded border-gray-300">
                        <span class="text-sm font-medium text-gray-700">{{ $fas }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Foto dan Media (Baru) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
            <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Foto & Media</h2>
            
            {{-- Preview & Input Foto Utama --}}
            <div class="p-4 border border-gray-200 rounded-xl bg-gray-50">
                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Utama Saat Ini</label>
                <img src="{{ $kos->foto_utama_url }}" class="w-40 h-28 object-cover rounded-lg mb-3 shadow-sm border border-gray-200">
                <input type="file" name="foto_utama" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-gray-700 hover:file:bg-gray-100">
                <p class="text-xs text-gray-400 mt-2">*Abaikan jika tidak ingin mengganti foto utama.</p>
            </div>

            {{-- Kategori Foto Tambahan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Tambahan (Galeri)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach(['kamar' => 'Kamar', 'kamar_mandi' => 'Kamar Mandi', 'dapur' => 'Dapur', 'fasilitas' => 'Fasilitas Umum'] as $key => $label)
                    <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm">
                        <label class="block text-xs font-bold text-gray-600 mb-2">{{ $label }}</label>
                        
                        @if(isset($kos->foto_tambahan[$key]))
                            <img src="{{ asset('storage/' . $kos->foto_tambahan[$key]) }}" class="w-full h-24 object-cover rounded-md mb-2 border border-gray-200">
                        @else
                            <div class="w-full h-24 bg-gray-100 rounded-md flex items-center justify-center mb-2 border border-dashed border-gray-300">
                                <span class="text-[10px] text-gray-400 font-medium">Belum ada foto</span>
                            </div>
                        @endif

                        <input type="file" name="foto_tambahan[{{ $key }}]" accept="image/*" class="w-full text-[10px] text-gray-500">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kontak & Status --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Kontak & Status</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Pemilik <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $kos->nama_pemilik) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="telepon_pemilik" value="{{ old('telepon_pemilik', $kos->telepon_pemilik) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Status Kamar</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_available" value="1" {{ $kos->is_available?'checked':'' }}
                               class="text-brand-500 focus:ring-brand-500 w-4 h-4">
                        <span class="text-sm text-gray-700">✅ Tersedia</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_available" value="0" {{ !$kos->is_available?'checked':'' }}
                               class="text-brand-500 focus:ring-brand-500 w-4 h-4">
                        <span class="text-sm text-gray-700">❌ Penuh</span>
                    </label>
                </div>
            </div>
        </div>
    </form> {{-- Tutup Form Update di sini --}}

    {{-- Form Delete Terpisah --}}
    <form id="form-delete" method="POST" action="{{ route('kos.destroy', $kos) }}" onsubmit="return confirm('Yakin ingin menghapus kos ini?')">
        @csrf 
        @method('DELETE')
    </form>

    {{-- Action Buttons --}}
    <div class="flex items-center justify-between gap-4 pt-6">
        {{-- Tombol Delete memanggil form-delete --}}
        <button type="submit" form="form-delete"
                class="flex items-center gap-2 text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-4 py-2.5 rounded-xl text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus Kos
        </button>

        <div class="flex gap-3">
            <a href="{{ route('kos.show', $kos) }}"
               class="px-5 py-2.5 border border-gray-200 text-gray-600 hover:border-gray-300 rounded-xl text-sm font-medium transition">
                Batal
            </a>
            {{-- Tombol Simpan memanggil form-update --}}
            <button type="submit" form="form-update"
                    class="flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-7 py-2.5 rounded-xl text-sm shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </div>

</div>
@endsection