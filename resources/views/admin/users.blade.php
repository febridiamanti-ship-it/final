@extends('layouts.app')
@section('title', 'Kelola User – Admin BaKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-brand-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="font-display text-2xl font-bold text-gray-900">Kelola Pengguna</h1>
            <p class="text-gray-400 text-sm">Total {{ $users->total() }} pengguna terdaftar</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.users') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <select name="role" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
            <option value="">Semua Role</option>
            <option value="pencari"  {{ request('role')==='pencari'  ? 'selected':'' }}>Pencari Kos</option>
            <option value="pemilik"  {{ request('role')==='pemilik'  ? 'selected':'' }}>Pemilik Kos</option>
            <option value="admin"    {{ request('role')==='admin'    ? 'selected':'' }}>Admin</option>
        </select>
        <button type="submit" class="bakos-gradient text-white font-medium px-5 py-2.5 rounded-xl text-sm">Cari</button>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3 hidden md:table-cell">Kontak</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 hidden sm:table-cell">Bergabung</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $u)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $u->foto_profil_url }}" alt="{{ $u->name }}"
                                     class="w-10 h-10 rounded-full object-cover shrink-0">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell text-gray-500">
                            {{ $u->telepon ?? '–' }}
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.users.role', $u) }}">
                                @csrf @method('PUT')
                                <select name="role" onchange="this.form.submit()"
                                        class="text-xs font-semibold px-2.5 py-1.5 rounded-full border-0 focus:ring-2 focus:ring-brand-500 cursor-pointer
                                            {{ $u->isAdmin() ? 'bg-red-100 text-red-700' : ($u->isPemilik() ? 'bg-blue-100 text-brand-700' : 'bg-gray-100 text-gray-600') }}">
                                    <option value="pencari" {{ $u->isPencari() ? 'selected':'' }}>Pencari</option>
                                    <option value="pemilik" {{ $u->isPemilik() ? 'selected':'' }}>Pemilik</option>
                                    <option value="admin"   {{ $u->isAdmin()   ? 'selected':'' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell text-gray-400 text-xs">
                            {{ $u->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.delete', $u) }}"
                                  onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-gray-300 px-2">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
