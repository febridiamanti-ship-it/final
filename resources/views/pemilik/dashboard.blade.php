@extends('layouts.app')
@section('title', 'Dashboard Pemilik – BaKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <div>
            <p class="text-brand-500 text-sm font-semibold uppercase tracking-wide mb-1">Dashboard Pemilik</p>
            <h1 class="font-display text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-400 text-sm mt-0.5">Kelola semua kos yang Anda miliki</p>
        </div>
        <a href="{{ route('kos.create') }}"
           class="bakos-gradient text-white font-semibold px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 hover:shadow-md transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kos
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label'=>'Total Kos',   'value'=>$stats['total'],     'icon'=>'🏠', 'color'=>'blue'],
            ['label'=>'Tersedia',    'value'=>$stats['available'], 'icon'=>'✅', 'color'=>'green'],
            ['label'=>'Penuh',       'value'=>$stats['penuh'],     'icon'=>'❌', 'color'=>'red'],
            ['label'=>'Total Favorit','value'=>$stats['favorit'],  'icon'=>'❤️', 'color'=>'pink'],
        ] as $stat)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <div class="text-2xl mb-1">{{ $stat['icon'] }}</div>
            <div class="text-3xl font-display font-bold text-gray-900">{{ $stat['value'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Daftar Kos --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Daftar Kos Saya</h2>
        </div>

        @if($kosList->isEmpty())
        <div class="p-16 text-center">
            <div class="text-5xl mb-3">🏘️</div>
            <p class="text-gray-400 text-sm">Belum ada kos. Yuk tambah kos pertama!</p>
            <a href="{{ route('kos.create') }}" class="bakos-gradient text-white font-semibold px-5 py-2.5 rounded-xl text-sm inline-block mt-4 hover:shadow-md transition">
                Tambah Kos
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <th class="px-6 py-3">Nama Kos</th>
                        <th class="px-6 py-3 hidden md:table-cell">Lokasi</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($kosList as $kos)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $kos->foto_utama_url }}" alt="{{ $kos->nama }}"
                                     class="w-12 h-10 rounded-xl object-cover shrink-0"
                                     onerror="this.src='https://placehold.co/48x40/2563eb/white?text=BK'">
                                <div>
                                    <p class="font-medium text-gray-900">{{ Str::limit($kos->nama, 30) }}</p>
                                    <p class="text-xs text-gray-400 capitalize">{{ $kos->jenis }} · {{ $kos->tipe_kamar }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell text-gray-500">
                            {{ $kos->kecamatan }}, {{ $kos->kota }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-brand-600">{{ $kos->harga_format }}</span>
                            <span class="text-gray-400 text-xs">/bln</span>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('pemilik.toggle', $kos) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs font-semibold px-3 py-1.5 rounded-full transition
                                            {{ $kos->is_available
                                                ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                    {{ $kos->is_available ? '✅ Tersedia' : '❌ Penuh' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('kos.show', $kos) }}"
                                   class="p-2 text-gray-400 hover:text-brand-500 hover:bg-blue-50 rounded-lg transition" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('kos.edit', $kos) }}"
                                   class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('kos.destroy', $kos) }}"
                                      onsubmit="return confirm('Yakin hapus kos ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($kosList->hasPages())
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $kosList->links('vendor.pagination.tailwind') }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
