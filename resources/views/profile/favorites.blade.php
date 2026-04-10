@extends('layouts.app')
@section('title', 'Kos Favorit – BaKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-2xl font-bold text-gray-900">❤️ Kos Favorit Saya</h1>
            <p class="text-gray-400 text-sm mt-1">{{ $favorites->total() }} kos tersimpan</p>
        </div>
        <a href="{{ route('kos.index') }}" class="text-sm text-brand-500 hover:underline font-medium">Cari lebih banyak →</a>
    </div>

    @if($favorites->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
        <div class="text-5xl mb-4">💔</div>
        <h3 class="font-semibold text-gray-900 mb-2">Belum ada kos favorit</h3>
        <p class="text-gray-400 text-sm mb-4">Tekan ikon ❤️ di halaman detail kos untuk menyimpannya</p>
        <a href="{{ route('kos.index') }}" class="bakos-gradient text-white font-semibold px-6 py-2.5 rounded-xl text-sm inline-block hover:shadow-md transition">
            Cari Kos Sekarang
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($favorites as $kos)
        <div class="relative group">
            <x-kos-card :kos="$kos"/>
            {{-- Remove favorite button --}}
            <form method="POST" action="{{ route('favorites.toggle', $kos) }}" class="absolute top-3 right-3 z-10">
                @csrf
                <button type="submit"
                        class="w-8 h-8 bg-white/90 hover:bg-red-50 rounded-full flex items-center justify-center shadow transition"
                        title="Hapus dari favorit">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                    </svg>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <div class="mt-8">{{ $favorites->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection
