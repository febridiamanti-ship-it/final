@extends('layouts.app')
@section('title', 'Profil Saya – BaKos')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header Profil --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="h-24 bakos-gradient relative">
            <div class="absolute inset-0 opacity-10" style="background-image:linear-gradient(#fff 1px,transparent 1px),linear-gradient(90deg,#fff 1px,transparent 1px);background-size:30px 30px"></div>
        </div>
        <div class="px-6 pb-6">
            <div class="flex items-end justify-between -mt-10 mb-4 flex-wrap gap-4">
                <div class="relative">
                    <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-2xl border-4 border-white shadow-md object-cover">
                    <span class="absolute -bottom-1 -right-1 text-xs font-bold px-2 py-0.5 rounded-full
                        {{ $user->isAdmin() ? 'bg-red-500 text-white' : ($user->isPemilik() ? 'bg-blue-600 text-white' : 'bg-gray-600 text-white') }}">
                        {{ $user->role_label }}
                    </span>
                </div>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-2 border border-gray-200 hover:border-brand-300 hover:text-brand-600 text-gray-600 px-4 py-2 rounded-xl text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Profil
                </a>
            </div>
            <h1 class="font-display text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ $user->email }}</p>
            @if($user->bio)
            <p class="text-gray-600 text-sm mt-2 max-w-xl">{{ $user->bio }}</p>
            @endif
            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500">
                @if($user->telepon)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $user->telepon }}
                </span>
                @endif
                @if($user->kota)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $user->kota }}
                </span>
                @endif
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bergabung {{ $user->created_at->translatedFormat('F Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Quick Links --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-semibold text-gray-900 text-sm mb-4">Menu</h2>
                <nav class="space-y-1">
                    @foreach([
                        ['route' => 'profile.show',   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profil Saya'],
                        ['route' => 'favorites.index', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Kos Favorit'],
                    ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                              {{ request()->routeIs($item['route']) ? 'bg-brand-50 text-brand-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach

                    @if(auth()->user()->isPemilik())
                    <a href="{{ route('pemilik.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/></svg>
                        Dashboard Pemilik
                    </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Panel Admin
                    </a>
                    @endif

                    <hr class="border-gray-100 my-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Right: Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Favorit terbaru --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">❤️ Kos Favorit Terbaru</h2>
                    <a href="{{ route('favorites.index') }}" class="text-sm text-brand-500 hover:underline">Lihat semua</a>
                </div>
                @if($favorites->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <div class="text-4xl mb-2">🏘️</div>
                    <p class="text-sm">Belum ada kos favorit.</p>
                    <a href="{{ route('kos.index') }}" class="text-brand-500 text-sm font-medium hover:underline mt-1 inline-block">Cari kos sekarang →</a>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($favorites->take(4) as $kos)
                    <a href="{{ route('kos.show', $kos) }}"
                       class="group flex gap-3 p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition">
                        <img src="{{ $kos->foto_utama_url }}" alt="{{ $kos->nama }}"
                             class="w-16 h-14 rounded-xl object-cover shrink-0"
                             onerror="this.src='https://placehold.co/64x56/2563eb/white?text=BK'">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 text-sm truncate group-hover:text-brand-600 transition">{{ $kos->nama }}</p>
                            <p class="text-gray-400 text-xs truncate">{{ $kos->kecamatan }}, {{ $kos->kota }}</p>
                            <p class="text-brand-600 font-semibold text-sm mt-0.5">{{ $kos->harga_format }}<span class="text-gray-400 font-normal">/bln</span></p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Kos milik pemilik --}}
            @if($user->isPemilik() && $myKos->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-gray-900">🏠 Kos Saya</h2>
                    <a href="{{ route('pemilik.dashboard') }}" class="text-sm text-brand-500 hover:underline">Kelola →</a>
                </div>
                <div class="space-y-3">
                    @foreach($myKos->take(3) as $kos)
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100">
                        <img src="{{ $kos->foto_utama_url }}" alt="{{ $kos->nama }}"
                             class="w-14 h-12 rounded-xl object-cover shrink-0"
                             onerror="this.src='https://placehold.co/56x48/2563eb/white?text=BK'">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm truncate">{{ $kos->nama }}</p>
                            <p class="text-gray-400 text-xs">{{ $kos->harga_format }}/bln</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full shrink-0
                            {{ $kos->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ $kos->is_available ? 'Tersedia' : 'Penuh' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
