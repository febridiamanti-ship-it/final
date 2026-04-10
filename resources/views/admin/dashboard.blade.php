{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Admin Dashboard – BaKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    {{-- ── Page Header ── --}}
    <div class="reveal mb-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 bg-rose-50 text-rose-600 text-xs font-bold px-3.5 py-1.5 rounded-full mb-3 border border-rose-100 tracking-wide uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Panel Admin
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Halo, <span class="text-gradient-animate">{{ Str::words(auth()->user()->name, 1, '') }}</span> 👋
                </h1>
                <p class="text-slate-500 mt-1.5 font-medium">
                    Pantau dan kelola seluruh data BaKos.
                    <span class="text-slate-400 text-sm ml-1">{{ now()->translatedFormat('l, d F Y') }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('kos.create') }}" class="btn-press inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-md shadow-brand-200/40 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kos
                </a>
                <a href="{{ route('home') }}" target="_blank" class="btn-press inline-flex items-center gap-2 bg-white text-slate-600 font-bold px-5 py-2.5 rounded-xl text-sm border border-slate-200 hover:border-slate-300 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Website
                </a>
            </div>
        </div>
    </div>


    {{-- ── Stat Cards ── --}}
    @php
        $statCards = [
            [
                'label' => 'Total Kos',
                'value' => $stats['total_kos'],
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                'color' => 'brand',
                'bg'    => 'from-brand-50 to-brand-100/50',
                'iconBg'=> 'bg-brand-100 text-brand-600',
                'sub'   => $stats['kos_available'] . ' unit tersedia',
                'trend' => '+12%',
                'up'    => true,
            ],
            [
                'label' => 'Total Pengguna',
                'value' => $stats['total_users'],
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
                'color' => 'violet',
                'bg'    => 'from-violet-50 to-violet-100/50',
                'iconBg'=> 'bg-violet-100 text-violet-600',
                'sub'   => $stats['total_pemilik'] . ' pemilik kos',
                'trend' => '+8%',
                'up'    => true,
            ],
            [
                'label' => 'Pencari Kos',
                'value' => $stats['total_pencari'],
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                'color' => 'emerald',
                'bg'    => 'from-emerald-50 to-emerald-100/50',
                'iconBg'=> 'bg-emerald-100 text-emerald-600',
                'sub'   => 'pengguna aktif',
                'trend' => '+15%',
                'up'    => true,
            ],
            [
                'label' => 'Kos Penuh',
                'value' => ($stats['total_kos'] - $stats['kos_available']),
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                'color' => 'amber',
                'bg'    => 'from-amber-50 to-amber-100/50',
                'iconBg'=> 'bg-amber-100 text-amber-600',
                'sub'   => 'dari total ' . $stats['total_kos'] . ' kos',
                'trend' => round($stats['total_kos'] > 0 ? (($stats['total_kos'] - $stats['kos_available']) / $stats['total_kos'] * 100) : 0) . '%',
                'up'    => false,
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @foreach($statCards as $index => $stat)
            <div class="reveal group relative bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-[0_20px_50px_-15px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-500 overflow-hidden"
                 style="transition-delay: {{ $index * 0.08 }}s;">

                {{-- Gradient corner decoration --}}
                <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $stat['bg'] }} opacity-60 group-hover:opacity-100 group-hover:scale-125 transition-all duration-500"></div>

                <div class="relative">
                    {{-- Header: Icon + Trend --}}
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-12 h-12 {{ $stat['iconBg'] }} rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                        </div>
                        <div class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full {{ $stat['up'] ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                            @if($stat['up'])
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17l9.2-9.2M17 17V7H7"/></svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 7l-9.2 9.2M7 7v10h10"/></svg>
                            @endif
                            {{ $stat['trend'] }}
                        </div>
                    </div>

                    {{-- Value --}}
                    <div class="text-3xl font-extrabold text-slate-900 tracking-tight mb-1">
                        {{ number_format($stat['value']) }}
                    </div>
                    <div class="text-sm font-semibold text-slate-500">{{ $stat['label'] }}</div>
                    <div class="text-xs text-slate-400 mt-1">{{ $stat['sub'] }}</div>
                </div>
            </div>
        @endforeach
    </div>


    {{-- ── Quick Overview Bar ── --}}
    <div class="reveal bg-white rounded-2xl border border-slate-100 p-5 mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            {{-- Availability --}}
            <div class="flex items-center gap-4 flex-1 w-full">
                <span class="text-sm font-semibold text-slate-500 shrink-0">Ketersediaan Kos</span>
                <div class="flex-1 max-w-xs">
                    @php $pct = $stats['total_kos'] > 0 ? round($stats['kos_available'] / $stats['total_kos'] * 100) : 0; @endphp
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-brand-400 to-brand-500 rounded-full transition-all duration-1000 ease-out" style="width: {{ $pct }}%;"></div>
                    </div>
                </div>
                <span class="text-sm font-bold text-brand-600">{{ $pct }}%</span>
            </div>

            {{-- Distribution dots --}}
            <div class="flex items-center gap-5 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-violet-500 shadow-sm shadow-violet-200"></div>
                    <span class="text-xs text-slate-500 font-medium">Pemilik: <span class="font-bold text-slate-700">{{ $stats['total_pemilik'] }}</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div>
                    <span class="text-xs text-slate-500 font-medium">Pencari: <span class="font-bold text-slate-700">{{ $stats['total_pencari'] }}</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></div>
                    <span class="text-xs text-slate-500 font-medium">Admin: <span class="font-bold text-slate-700">{{ max($stats['total_users'] - $stats['total_pemilik'] - $stats['total_pencari'], 0) }}</span></span>
                </div>
            </div>
        </div>
    </div>


    {{-- ── Main Grid: Kos Terbaru + Pengguna Terbaru ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Kos Terbaru --}}
        <div class="reveal bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-brand-500 shadow-sm shadow-brand-200"></div>
                    <h2 class="font-bold text-slate-900 text-sm">Kos Terbaru</h2>
                </div>
                <a href="{{ route('admin.kos') }}" class="group inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 bg-brand-50 hover:bg-brand-100 px-3.5 py-1.5 rounded-lg transition-colors">
                    Lihat Semua
                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($latestKos->count() > 0)
                <div class="divide-y divide-slate-50">
                    @foreach($latestKos as $kos)
                        <div class="group/row px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                            {{-- Thumbnail --}}
                            <div class="relative shrink-0">
                                <img src="{{ $kos->foto_utama_url }}" alt="{{ $kos->nama }}"
                                     class="w-12 h-11 rounded-xl object-cover border border-slate-100 group-hover/row:border-brand-200 transition-colors"
                                     onerror="this.src='https://placehold.co/48x44/f0fdfa/0d9488?text=BK'">
                                {{-- Availability dot --}}
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white {{ $kos->is_available ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm truncate group-hover/row:text-brand-700 transition-colors">{{ $kos->nama }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $kos->kota }} · {{ $kos->user?->name ?? 'Tanpa pemilik' }}</p>
                            </div>
                            {{-- Price & Status --}}
                            <div class="text-right shrink-0">
                                <p class="text-brand-600 font-bold text-sm">{{ $kos->harga_format }}</p>
                                <span class="text-xs font-semibold {{ $kos->is_available ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ $kos->is_available ? '✓ Tersedia' : '✕ Penuh' }}
                                </span>
                            </div>
                            {{-- Actions --}}
                            <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover/row:opacity-100 transition-opacity">
                                <a href="{{ route('kos.show', $kos) }}" class="w-8 h-8 rounded-lg bg-brand-50 hover:bg-brand-100 flex items-center justify-center text-brand-600 transition-colors" title="Lihat">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('kos.edit', $kos) }}" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 flex items-center justify-center text-amber-600 transition-colors" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl mx-auto mb-4 flex items-center justify-center animate-float">
                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <p class="text-slate-500 font-semibold">Belum ada data kos</p>
                    <p class="text-slate-400 text-sm mt-1">Kos yang ditambahkan akan muncul di sini.</p>
                </div>
            @endif
        </div>

        {{-- Pengguna Terbaru --}}
        <div class="reveal bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-violet-500 shadow-sm shadow-violet-200"></div>
                    <h2 class="font-bold text-slate-900 text-sm">Pengguna Terbaru</h2>
                </div>
                <a href="{{ route('admin.users') }}" class="group inline-flex items-center gap-1.5 text-xs font-bold text-violet-600 bg-violet-50 hover:bg-violet-100 px-3.5 py-1.5 rounded-lg transition-colors">
                    Lihat Semua
                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($latestUsers->count() > 0)
                <div class="divide-y divide-slate-50">
                    @foreach($latestUsers as $u)
                        <div class="group/row px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                            {{-- Avatar --}}
                            <div class="relative shrink-0">
                                <img src="{{ $u->foto_profil_url }}" alt="{{ $u->name }}"
                                     class="w-10 h-10 rounded-xl object-cover border-2 border-slate-100 group-hover/row:border-violet-200 transition-colors">
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white {{ $u->updated_at->diffInMinutes(now()) < 30 ? 'bg-emerald-400' : 'bg-slate-300' }}"></div>
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm group-hover/row:text-violet-700 transition-colors">{{ Str::limit($u->name, 20) }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $u->email }}</p>
                            </div>
                            {{-- Role Badge --}}
                            <div class="shrink-0">
                                @if($u->isAdmin())
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-100">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Admin
                                    </span>
                                @elseif($u->isPemilik())
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg border border-brand-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        Pemilik
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-lg border border-sky-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Pencari
                                    </span>
                                @endif
                            </div>
                            {{-- Time --}}
                            <div class="text-right shrink-0 hidden sm:block">
                                <p class="text-xs text-slate-400 font-medium">{{ $u->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl mx-auto mb-4 flex items-center justify-center animate-float">
                        <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-slate-500 font-semibold">Belum ada pengguna</p>
                    <p class="text-slate-400 text-sm mt-1">Pengguna baru akan tampil di sini.</p>
                </div>
            @endif
        </div>
    </div>


    {{-- ── Activity + Distribution Row ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Activity Timeline (2/3) --}}
        <div class="reveal lg:col-span-2 bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div>
                <h2 class="font-bold text-slate-900 text-sm">Aktivitas Terakhir</h2>
            </div>
            <div class="p-6">
                @php
                    $activities = collect();
                    foreach($latestKos->take(3) as $k) {
                        $activities->push([
                            'type' => 'kos',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                            'color' => 'brand',
                            'text' => 'Kos <strong class="text-slate-700">' . Str::limit($k->nama, 22) . '</strong> ditambahkan',
                            'by' => $k->user?->name ?? 'Sistem',
                            'time' => $k->created_at,
                        ]);
                    }
                    foreach($latestUsers->take(3) as $u) {
                        $activities->push([
                            'type' => 'user',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>',
                            'color' => 'violet',
                            'text' => '<strong class="text-slate-700">' . Str::limit($u->name, 18) . '</strong> bergabung sebagai ' . $u->role_label,
                            'by' => null,
                            'time' => $u->created_at,
                        ]);
                    }
                    $activities = $activities->sortByDesc('time')->take(6);
                @endphp

                @if($activities->count() > 0)
                    <div class="space-y-0">
                        @foreach($activities as $act)
                            <div class="flex gap-4 py-3.5 {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                                {{-- Icon --}}
                                <div class="shrink-0 w-9 h-9 rounded-xl bg-{{ $act['color'] }}-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-{{ $act['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $act['icon'] !!}</svg>
                                </div>
                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-500 leading-relaxed">{!! $act['text'] !!}</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        @if($act['by'])
                                            <span class="text-xs text-slate-400 font-medium">oleh {{ $act['by'] }}</span>
                                            <span class="text-slate-200">·</span>
                                        @endif
                                        <span class="text-xs text-slate-400">{{ $act['time']->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl mx-auto mb-3 flex items-center justify-center animate-float">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-slate-500 font-semibold text-sm">Belum ada aktivitas</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Distribution (1/3) --}}
        <div class="reveal bg-white rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-amber-500 shadow-sm shadow-amber-200"></div>
                <h2 class="font-bold text-slate-900 text-sm">Distribusi</h2>
            </div>
            <div class="p-6">
                {{-- Role Distribution --}}
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Peran Pengguna</p>
                @php
                    $roles = [
                        ['label' => 'Pemilik', 'count' => $stats['total_pemilik'], 'color' => 'brand'],
                        ['label' => 'Pencari', 'count' => $stats['total_pencari'], 'color' => 'emerald'],
                        ['label' => 'Admin', 'count' => max($stats['total_users'] - $stats['total_pemilik'] - $stats['total_pencari'], 0), 'color' => 'rose'],
                    ];
                    $totalRoles = max(array_sum(array_column($roles, 'count')), 1);
                @endphp

                <div class="space-y-4 mb-6">
                    @foreach($roles as $role)
                        @php $rolePct = round($role['count'] / $totalRoles * 100); @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm text-slate-600 font-medium">{{ $role['label'] }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-900">{{ $role['count'] }}</span>
                                    <span class="text-xs text-slate-400">({{ $rolePct }}%)</span>
                                </div>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $role['color'] }}-500 rounded-full transition-all duration-1000" style="width: {{ $rolePct }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Kos Status --}}
                <div class="border-t border-slate-100 pt-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Status Kos</p>
                    @php
                        $available = $stats['kos_available'];
                        $full = $stats['total_kos'] - $stats['kos_available'];
                        $totalKos = max($stats['total_kos'], 1);
                    @endphp
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-center">
                            <div class="text-2xl font-extrabold text-emerald-600">{{ $available }}</div>
                            <div class="text-xs font-semibold text-emerald-500 mt-1">Tersedia</div>
                        </div>
                        <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-center">
                            <div class="text-2xl font-extrabold text-rose-600">{{ $full }}</div>
                            <div class="text-xs font-semibold text-rose-500 mt-1">Penuh</div>
                        </div>
                    </div>
                    {{-- Stacked bar --}}
                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden flex">
                        <div class="h-full bg-emerald-400 transition-all duration-1000" style="width: {{ round($available / $totalKos * 100) }}%;"></div>
                        <div class="h-full bg-rose-400 transition-all duration-1000" style="width: {{ round($full / $totalKos * 100) }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── Quick Navigation ── --}}
    <div class="reveal">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Akses Cepat
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $quickNav = [
                    [
                        'route' => 'admin.users',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        'label' => 'Kelola Pengguna',
                        'desc'  => 'Manage semua user',
                        'color' => 'violet',
                    ],
                    [
                        'route' => 'admin.kos',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                        'label' => 'Kelola Kos',
                        'desc'  => 'Data & listing kos',
                        'color' => 'brand',
                    ],
                    [
                        'route' => 'kos.create',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>',
                        'label' => 'Tambah Kos',
                        'desc'  => 'Buat listing baru',
                        'color' => 'emerald',
                    ],
                    [
                        'route' => 'home',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>',
                        'label' => 'Lihat Website',
                        'desc'  => 'Preview publik',
                        'color' => 'amber',
                    ],
                ];
            @endphp

            @foreach($quickNav as $index => $nav)
                <a href="{{ route($nav['route']) }}"
                   {{ $nav['route'] === 'home' ? 'target=_blank' : '' }}
                   class="group bg-white border border-slate-100 rounded-2xl p-5 hover:border-{{ $nav['color'] }}-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                   style="transition-delay: {{ $index * 0.05 }}s;">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-{{ $nav['color'] }}-50 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:shadow-md group-hover:shadow-{{ $nav['color'] }}-100/50 transition-all duration-300">
                            <svg class="w-5 h-5 text-{{ $nav['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $nav['icon'] !!}</svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-{{ $nav['color'] }}-400 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm group-hover:text-{{ $nav['color'] }}-700 transition-colors">{{ $nav['label'] }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $nav['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>


    {{-- ── System Info ── --}}
    <div class="reveal mt-8 bg-white rounded-2xl border border-slate-100 px-6 py-3.5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-5">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-xs text-slate-500 font-medium">Sistem Online</span>
            </div>
            <span class="text-xs text-slate-400">Laravel v{{ app()->version() }}</span>
            <span class="text-xs text-slate-400">PHP {{ phpversion() }}</span>
        </div>
        <span class="text-xs text-slate-400">
            Terakhir login: {{ auth()->user()->updated_at->diffForHumans() }}
        </span>
    </div>

</div>
@endsection