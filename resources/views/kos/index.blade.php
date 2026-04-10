@extends('layouts.app')

@section('title', 'Cari Kos - BaKos')

@section('content')
<div class="bg-surface border-b border-slate-200 sticky top-20 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        {{-- Form Pencarian & Filter Minimalis --}}
        <form action="{{ route('kos.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center">
            
            {{-- Input Cari --}}
            <div class="relative w-full md:flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama tempat atau daerah..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:bg-white transition-all">
            </div>

            {{-- Filter Jenis --}}
            <div class="w-full md:w-auto flex gap-2">
                <select name="jenis" class="flex-1 md:w-40 py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-full text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="putra" {{ request('jenis') == 'putra' ? 'selected' : '' }}>Putra</option>
                    <option value="putri" {{ request('jenis') == 'putri' ? 'selected' : '' }}>Putri</option>
                    <option value="campur" {{ request('jenis') == 'campur' ? 'selected' : '' }}>Campur</option>
                </select>

                <select name="kota" class="flex-1 md:w-40 py-2.5 px-4 bg-slate-50 border border-slate-200 rounded-full text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer">
                    <option value="">Semua Kota</option>
                    <option value="Manado" {{ request('kota') == 'Manado' ? 'selected' : '' }}>Manado</option>
                    <option value="Minahasa" {{ request('kota') == 'Minahasa' ? 'selected' : '' }}>Minahasa</option>
                </select>

                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white p-2.5 rounded-full shadow-sm transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">
            {{ request('q') ? 'Hasil pencarian: "'.request('q').'"' : 'Semua Kos Tersedia' }}
        </h1>
        <p class="text-slate-500 mt-1 text-sm">Menampilkan {{ $kosList->total() }} kos yang sesuai dengan kriteria Anda.</p>
    </div>

    {{-- Grid Kos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        @forelse($kosList as $kos)
            <x-kos-card :kos="$kos"/>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Kos tidak ditemukan</h3>
                <p class="text-slate-500 text-sm max-w-sm mt-1">Coba ubah kata kunci pencarian atau sesuaikan filter Anda untuk melihat lebih banyak hasil.</p>
                <a href="{{ route('kos.index') }}" class="mt-6 text-brand-600 font-semibold text-sm hover:underline">Reset Pencarian</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination Minimalis --}}
    <div class="mt-12">
        {{ $kosList->withQueryString()->links() }}
    </div>

</div>
@endsection