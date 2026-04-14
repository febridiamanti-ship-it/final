@extends('layouts.app')
@section('title', 'Edit Profil – BaKos')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data>

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('profile.show') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Profil</h1>
            <p class="text-slate-500 text-sm mt-1">Perbarui informasi akun dan keamanan Anda.</p>
        </div>
    </div>

    {{-- Edit Info Card --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-float p-8 mb-8">
        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Informasi Akun
        </h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            {{-- Foto Profil --}}
            <div class="flex items-center gap-6 p-4 bg-slate-50 rounded-2xl border border-slate-100" x-data="{ preview: null }">
                <div class="relative">
                    <img :src="preview ?? '{{ $user->foto_profil_url }}'"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                    <button type="button" onclick="document.getElementById('foto_input').click()"
                            class="absolute bottom-0 right-0 w-8 h-8 bg-brand-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-brand-700 transition-colors border-2 border-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Foto Profil</h3>
                    <p class="text-xs text-slate-500 mb-3">Format JPG, PNG. Maksimal ukuran 2MB.</p>
                    <button type="button" onclick="document.getElementById('foto_input').click()"
                            class="text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg transition-colors shadow-sm">
                        Pilih Gambar Baru
                    </button>
                    <input type="file" id="foto_input" name="foto_profil" accept="image/*" class="hidden"
                           @change="const f=$event.target.files[0]; if(f){const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL(f)}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-slate-100 pt-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email (Tidak bisa diubah)</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">No. HP / WhatsApp</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kota Domisili</label>
                    <input type="text" name="kota" value="{{ old('kota', $user->kota) }}" placeholder="Contoh: Manado"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Bio Singkat</label>
                <textarea name="bio" rows="3" maxlength="500" placeholder="Ceritakan sedikit tentang dirimu..."
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all resize-none">{{ old('bio', $user->bio) }}</textarea>
                <p class="text-xs text-slate-400 mt-2 text-right">Maks. 500 karakter</p>
            </div>

            {{-- Tombol Simpan Profil (Sekarang sangat jelas!) --}}
            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit"
                        class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- Ganti Password Card --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-float p-8">
        <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Ganti Keamanan (Password)
        </h2>

        <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
            @csrf @method('PUT')

            @if(session('success') && str_contains(session('success'), 'Password'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl px-4 py-3 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full md:w-1/2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 @error('current_password') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div class="border-t border-slate-100 pt-6 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection