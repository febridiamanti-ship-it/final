@extends('layouts.app')

@section('title', 'BaKos – Cari Kos Tanpa Ribet')

@push('styles')
<style>
    /* ═══ CUSTOM ANIMATIONS ═══ */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(-10px) rotate(1deg); }
        66% { transform: translateY(-5px) rotate(-1deg); }
    }

    @keyframes float-reverse {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(8px) rotate(-1deg); }
        66% { transform: translateY(3px) rotate(1deg); }
    }

    @keyframes blob {
        0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        50% { border-radius: 50% 60% 30% 60% / 30% 60% 70% 40%; }
        75% { border-radius: 60% 40% 60% 30% / 60% 40% 30% 70%; }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes fade-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes slide-right {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2.2); opacity: 0; }
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }
    .animate-float-slow { animation: float 10s ease-in-out infinite; }
    .animate-blob { animation: blob 8s ease-in-out infinite; }
    .animate-blob-slow { animation: blob 12s ease-in-out infinite; }
    .animate-fade-up { animation: fade-up 0.8s ease-out forwards; }
    .animate-scale-in { animation: scale-in 0.6s ease-out forwards; }
    .animate-slide-right { animation: slide-right 0.6s ease-out forwards; }
    .animate-marquee { animation: marquee 25s linear infinite; }
    .animate-gradient { animation: gradient-shift 4s ease infinite; background-size: 200% 200%; }

    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-700 { animation-delay: 0.7s; }

    /* Scroll reveal base */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Glass morphism utility */
    .glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    .glass-dark {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }

    /* Shimmer effect for loading states */
    .shimmer {
        background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.4) 50%, transparent 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    /* Smooth hover card tilt */
    .tilt-card {
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    /* Custom scrollbar */
    .custom-scroll::-webkit-scrollbar { height: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Text gradient animation */
    .text-gradient-animate {
        background: linear-gradient(135deg, #10b981, #14b8a6, #06b6d4, #10b981);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 4s ease infinite;
    }

    /* Organic shape divider */
    .wave-divider {
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    /* Noise texture overlay */
    .noise::after {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0.03;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 1;
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════ --}}
{{--  STATS MARQUEE BANNER                           --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="relative py-6 bg-gradient-to-r from-brand-500 via-brand-600 to-teal-500 overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
    <div class="flex overflow-hidden">
        <div class="animate-marquee flex shrink-0 items-center gap-12 px-6">
            @foreach([
                ['5+', 'Kos Terdaftar'],
                ['25+', 'Pengguna Aktif'],
                ['4.9★', 'Rating Rata-rata'],
                ['100%', 'Gratis Tanpa Biaya'],
                ['24/7', 'Dukungan Online'],
                ['5+', 'Kos Terdaftar'],
                ['25+', 'Pengguna Aktif'],
                ['4.9★', 'Rating Rata-rata'],
                ['100%', 'Gratis Tanpa Biaya'],
                ['24/7', 'Dukungan Online'],
            ] as [$num, $label])
                <div class="flex items-center gap-3 text-white whitespace-nowrap">
                    <span class="text-2xl font-extrabold">{{ $num }}</span>
                    <span class="text-brand-100 text-sm font-medium">{{ $label }}</span>
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-white/30 shrink-0"></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ --}}
{{--  HERO SECTION – Organic, Alive, Immersive       --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="relative min-h-[95vh] flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 overflow-hidden noise" id="hero">

    {{-- Animated Organic Background Blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        {{-- Primary blob --}}
        <div class="absolute top-[-20%] left-[-10%] w-[700px] h-[700px] bg-gradient-to-br from-brand-300/30 to-teal-200/20 animate-blob opacity-70 blur-3xl"></div>
        {{-- Secondary blob --}}
        <div class="absolute bottom-[-15%] right-[-10%] w-[600px] h-[600px] bg-gradient-to-tl from-brand-200/25 to-emerald-100/20 animate-blob-slow opacity-60 blur-3xl" style="animation-delay: -4s;"></div>
        {{-- Accent blob --}}
        <div class="absolute top-[30%] right-[5%] w-[300px] h-[300px] bg-gradient-to-br from-teal-300/20 to-cyan-200/15 animate-blob opacity-50 blur-2xl" style="animation-delay: -2s;"></div>

        {{-- Floating decorative elements --}}
        <div class="absolute top-[15%] left-[8%] animate-float hidden lg:block" style="animation-delay: -1s;">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-100 to-brand-200/50 border border-white/60 shadow-lg shadow-brand-200/30 rotate-12 flex items-center justify-center">
                <svg class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
        </div>
        <div class="absolute top-[20%] right-[12%] animate-float-reverse hidden lg:block" style="animation-delay: -3s;">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-100 to-teal-200/50 border border-white/60 shadow-lg shadow-teal-200/30 -rotate-6 flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <div class="absolute bottom-[25%] left-[15%] animate-float-slow hidden lg:block" style="animation-delay: -5s;">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-100 to-amber-200/50 border border-white/60 shadow-lg shadow-amber-200/30 rotate-6 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
        </div>

        {{-- Subtle grid pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #64748b 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    {{-- Main Content --}}
    <div class="relative z-10 max-w-5xl mx-auto">

        {{-- Hero Headline --}}
        <div class="animate-fade-up opacity-0" style="animation-delay: 0.25s;">
            <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold text-slate-900 tracking-tight mb-8 leading-[1.05]">
                <span class="block">Cari kost?</span>
                <span class="block mt-2 text-gradient-animate">bakos solusinya</span>
            </h1>
        </div>

        {{-- Subtitle --}}
        <div class="animate-fade-up opacity-0" style="animation-delay: 0.4s;">
            <p class="text-lg md:text-xl text-slate-500 mb-14 max-w-2xl mx-auto leading-relaxed font-medium">
                Temukan kos terbaik di area Pandu dan sekitarnya. 
                <span class="text-slate-700">Harga transparan</span>, fasilitas lengkap, dan 
                <span class="text-slate-700">langsung terhubung</span> dengan pemilik.
            </p>
        </div>

        {{-- ── Search Bar – Glassmorphism + Soft Volume ── --}}
        <div class="animate-fade-up opacity-0" style="animation-delay: 0.55s;">
            <form action="{{ route('kos.index') }}" method="GET" class="max-w-3xl mx-auto">
                <div class="glass rounded-[2rem] p-2.5 border border-white/50 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.12),0_0_0_1px_rgba(255,255,255,0.5)_inset] transition-all duration-500 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.18)] hover:-translate-y-1 focus-within:ring-4 focus-within:ring-brand-400/20 focus-within:border-brand-300/50 group">
                    <div class="flex flex-col sm:flex-row items-center gap-1">

                        {{-- Search Input --}}
                        <div class="flex-1 flex items-center gap-3 px-5 py-3.5 w-full rounded-xl group-focus-within:bg-white/30 transition-colors">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-slate-100/80 flex items-center justify-center group-focus-within:bg-brand-50 group-focus-within:text-brand-500 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="q" placeholder="Cari fasilitas atau nama kos di Pandu..." class="w-full text-slate-900 placeholder-slate-400 text-base font-medium focus:outline-none bg-transparent">
                        </div>

                        {{-- Divider --}}
                        <div class="hidden sm:block w-px h-8 bg-slate-200/60 shrink-0"></div>

                        {{-- Type Select --}}
                        <div class="w-full sm:w-auto px-4 py-3">
                            <div class="relative">
                                <select name="jenis" class="w-full sm:w-36 text-slate-600 font-medium focus:outline-none bg-transparent cursor-pointer appearance-none pr-8 text-sm">
                                    <option value="">Semua Tipe</option>
                                    <option value="putra">🙋‍♂️ Kos Putra</option>
                                    <option value="putri">🙋‍♀️ Kos Putri</option>
                                    <option value="campur">👥 Kos Campur</option>
                                </select>
                                <svg class="absolute right-1 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        {{-- CTA Button --}}
                        <div class="w-full sm:w-auto p-1">
                            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold px-8 py-4 rounded-[1.2rem] transition-all duration-200 flex items-center justify-center gap-2.5 shadow-[0_8px_20px_-6px_rgba(16,185,129,0.5)] hover:shadow-[0_12px_28px_-6px_rgba(16,185,129,0.6)] hover:-translate-y-0.5 active:translate-y-0 active:shadow-[0_4px_12px_-4px_rgba(16,185,129,0.4)] group/btn">
                                <svg class="w-5 h-5 group-hover/btn:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Cari Kos</span>
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        {{-- Quick Tags --}}
        <div class="animate-fade-up opacity-0 mt-8" style="animation-delay: 0.7s;">
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
                <span class="text-slate-400 font-medium">Populer:</span>
                @foreach(['Kos Murah Pandu', 'Kos Putri AC', 'Dekat Jalan Utama Pandu', 'WiFi Gratis'] as $tag)
                    <a href="{{ route('kos.index', ['q' => $tag]) }}" class="px-3.5 py-1.5 rounded-full bg-white/60 border border-slate-200/50 text-slate-500 font-medium hover:bg-brand-50 hover:border-brand-200 hover:text-brand-600 transition-all duration-200 hover:-translate-y-0.5 shadow-sm hover:shadow-md">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Trust Indicators --}}
        <div class="animate-fade-up opacity-0 mt-14" style="animation-delay: 0.85s;">
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm text-slate-400">
                <div class="flex items-center gap-2">
                    <div class="flex -space-x-2">
                        @for($i = 0; $i < 4; $i++)
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 border-2 border-white ring-1 ring-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                {{ ['A','B','C','D'][$i] }}
                            </div>
                        @endfor
                    </div>
                    <span class="font-medium text-slate-500">25+ pengguna aktif</span>
                </div>
                <div class="hidden sm:block w-px h-4 bg-slate-200"></div>
                <div class="flex items-center gap-1.5">
                    <div class="flex gap-0.5">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="font-medium text-slate-500">Rating 4.9/5</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Organic Wave Divider --}}
    <div class="wave-divider">
        <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 50L48 45C96 40 192 30 288 33C384 36 480 52 576 58C672 64 768 60 864 52C960 44 1056 32 1152 30C1248 28 1344 36 1392 40L1440 44V100H1392C1344 100 1248 100 1152 100C1056 100 960 100 864 100C768 100 672 100 576 100C480 100 384 100 288 100C192 100 96 100 48 100H0V50Z" fill="white"/>
        </svg>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ --}}
{{--  KOS UNGGULAN – Staggered Card Grid             --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="relative bg-white py-20" id="rekomendasi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="reveal flex flex-col sm:flex-row items-start sm:items-end justify-between mb-12 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 text-brand-600 bg-brand-50 px-3 py-1 rounded-full text-xs font-bold mb-3 tracking-wide uppercase">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-2 1-3 .5 1 1.5 2 1.5 3a3 3 0 01.12 1.62z"/></svg>
                    Trending
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Rekomendasi Kos Pandu</h2>
                <p class="text-slate-500 mt-2 text-lg font-medium">Pilihan kos terpopuler dan paling diminati minggu ini.</p>
            </div>
            <a href="{{ route('kos.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-brand-600 bg-brand-50 hover:bg-brand-100 px-5 py-2.5 rounded-xl transition-all duration-300 hover:shadow-md shrink-0">
                Lihat semua
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-7">
            @forelse($kosUnggulan as $index => $kos)
                <div class="reveal" style="transition-delay: {{ $index * 0.1 }}s;">
                    <x-kos-card :kos="$kos"/>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="max-w-sm mx-auto">
                        <div class="w-20 h-20 bg-slate-100 rounded-3xl mx-auto mb-6 flex items-center justify-center animate-float">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <p class="text-slate-500 font-semibold text-lg mb-2">Belum ada kos tersedia</p>
                        <p class="text-slate-400 text-sm">Kos baru akan segera ditambahkan. Cek lagi nanti ya!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ --}}
{{--  CARA KERJA – Interactive Bento Grid            --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="relative py-28 bg-slate-50/80 overflow-hidden" id="cara-kerja">
    {{-- Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[400px] h-[400px] bg-brand-100/30 animate-blob blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[350px] h-[350px] bg-teal-100/30 animate-blob-slow blur-3xl" style="animation-delay: -3s;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Section Header --}}
        <div class="reveal text-center max-w-2xl mx-auto mb-20">
            <div class="inline-flex items-center gap-2 text-brand-600 bg-white px-4 py-1.5 rounded-full text-xs font-bold mb-4 tracking-wide uppercase shadow-sm border border-brand-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Simpel & Cepat
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-5">
                Tiga langkah <span class="text-gradient-animate">mudah</span>
            </h2>
            <p class="text-slate-500 text-lg font-medium leading-relaxed">
                Temukan tempat tinggal impianmu tanpa harus panas-panasan keliling area Pandu.
            </p>
        </div>

        {{-- Steps Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            @foreach([
                [
                    'step' => '01',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                    'color' => 'brand',
                    'title' => 'Cari & Filter',
                    'desc' => 'Ketik nama lokasi atau fasilitas. Filter berdasarkan budget dan jenis kos yang kamu mau.',
                    'visual' => 'search',
                ],
                [
                    'step' => '02',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
                    'color' => 'teal',
                    'title' => 'Lihat Detail',
                    'desc' => 'Cek foto asli, fasilitas lengkap, lokasi presisi di peta, dan ulasan dari penghuni sebelumnya.',
                    'visual' => 'detail',
                ],
                [
                    'step' => '03',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>',
                    'color' => 'amber',
                    'title' => 'Hubungi Pemilik',
                    'desc' => 'Cocok? Langsung chat pemilik kos via WhatsApp. Tanpa biaya perantara atau admin tambahan.',
                    'visual' => 'chat',
                ],
            ] as $index => $item)
                <div class="reveal group" style="transition-delay: {{ $index * 0.15 }}s;">
                    <div class="relative bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100/80 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_50px_-15px_rgba(0,0,0,0.1)] transition-all duration-500 hover:-translate-y-3 overflow-hidden h-full">

                        {{-- Hover gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-{{ $item['color'] }}-50/0 to-{{ $item['color'] }}-100/0 group-hover:from-{{ $item['color'] }}-50/50 group-hover:to-{{ $item['color'] }}-100/20 transition-all duration-500 rounded-[2rem]"></div>

                        {{-- Background step number --}}
                        <div class="absolute -right-3 -bottom-6 text-[10rem] font-black text-slate-50 group-hover:text-{{ $item['color'] }}-50 transition-all duration-500 z-0 leading-none select-none">
                            {{ $item['step'] }}
                        </div>

                        {{-- Content --}}
                        <div class="relative z-10">
                            {{-- Icon --}}
                            <div class="w-14 h-14 bg-gradient-to-br from-{{ $item['color'] }}-50 to-{{ $item['color'] }}-100/80 rounded-2xl flex items-center justify-center mb-7 shadow-sm border border-{{ $item['color'] }}-100/50 group-hover:scale-110 group-hover:shadow-md group-hover:shadow-{{ $item['color'] }}-200/30 transition-all duration-500">
                                <svg class="w-6 h-6 text-{{ $item['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                            </div>

                            {{-- Step label --}}
                            <div class="text-xs font-bold text-{{ $item['color'] }}-500 tracking-widest uppercase mb-3">
                                Langkah {{ $item['step'] }}
                            </div>

                            <h3 class="text-xl md:text-2xl font-bold text-slate-900 mb-3 tracking-tight">{{ $item['title'] }}</h3>
                            <p class="text-slate-500 leading-relaxed font-medium">{{ $item['desc'] }}</p>

                            {{-- Decorative arrow connecting steps --}}
                            @if($index < 2)
                                <div class="hidden md:flex absolute -right-10 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-white shadow-md border border-slate-100 items-center justify-center text-slate-300 group-hover:text-brand-400 group-hover:border-brand-200 transition-colors duration-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ --}}
{{--  TESTIMONIALS – Social Proof Carousel           --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="py-24 bg-white overflow-hidden" id="testimoni">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="reveal text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 text-brand-600 bg-brand-50 px-4 py-1.5 rounded-full text-xs font-bold mb-4 tracking-wide uppercase shadow-sm border border-brand-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Mereka Sudah Merasakan
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Kata Mereka Tentang BaKos</h2>
            <p class="text-slate-500 text-lg font-medium">Dengarkan pengalaman nyata dari pengguna dan pemilik kos di platform kami.</p>
        </div>

        {{-- Testimonial Cards --}}
        <div class="reveal grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                // Array warna untuk variasi desain avatar
                $colors = ['brand', 'teal', 'amber'];
            @endphp

            @forelse($latestReviews as $index => $review)
                @php
                    $color = $colors[$index % 3]; // Menggilir warna secara otomatis
                    $initial = strtoupper(substr($review->user->name, 0, 2)); // Ambil 2 huruf pertama
                @endphp
                <div class="group bg-slate-50 hover:bg-white rounded-[2rem] p-8 border border-slate-100 hover:border-slate-200/80 hover:shadow-[0_20px_50px_-15px_rgba(0,0,0,0.08)] transition-all duration-500 hover:-translate-y-2" style="transition-delay: {{ $index * 0.1 }}s;">

                    {{-- Stars --}}
                    <div class="flex gap-1 mb-5">
                        @for($i = 0; $i < $review->rating; $i++)
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>

                    {{-- Quote --}}
                    <blockquote class="text-slate-600 leading-relaxed mb-8 font-medium text-[15px]">
                        "{{ Str::limit($review->komentar, 150) }}"
                    </blockquote>

                    {{-- Author --}}
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-{{ $color }}-400 to-{{ $color }}-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-{{ $color }}-200/40">
                            {{ $initial }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $review->user->name }}</div>
                            <div class="text-slate-400 text-xs font-medium">{{ $review->user->role_label ?? 'Pengguna BaKos' }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 text-slate-500 font-medium">
                    Belum ada ulasan yang ditampilkan. Jadilah yang pertama memberikan ulasan positif!
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Tampilkan HANYA JIKA belum login, ATAU jika login sebagai pemilik/admin --}}
@if(!auth()->check() || (auth()->user()?->isPemilik() || auth()->user()?->isAdmin()))
{{-- ═══════════════════════════════════════════════ --}}
{{--  CTA PASANG KOS – Immersive Dark Section        --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="relative py-8 px-4 sm:px-6 lg:px-8" id="pasang-kos">
    <div class="max-w-7xl mx-auto">
        <div class="reveal relative rounded-[3rem] overflow-hidden">

            {{-- Background Layers --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand-500/15 rounded-full blur-[100px] animate-blob"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-teal-500/10 rounded-full blur-[100px] animate-blob-slow" style="animation-delay: -4s;"></div>
            </div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 28px 28px;"></div>

            {{-- Content --}}
            <div class="relative z-10 px-8 py-16 md:px-16 md:py-24 flex flex-col lg:flex-row items-center justify-between gap-12">

                <div class="max-w-2xl text-center lg:text-left">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 glass-dark text-brand-300 text-xs font-bold px-4 py-2 rounded-full mb-8 border border-slate-600/30 uppercase tracking-widest">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-400"></span>
                        </span>
                        Untuk Pemilik Properti
                    </div>

                    <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight leading-[1.1]">
                        Punya kos di
                        <span class="text-gradient-animate">Pandu?</span>
                    </h2>
                    <p class="text-slate-300/90 text-lg md:text-xl leading-relaxed font-medium mb-8 max-w-xl">
                        Jangan biarkan kamar kosong. Pasang iklan properti Anda secara
                        <span class="inline-flex items-center gap-1 bg-white/10 text-white font-bold px-2.5 py-0.5 rounded-lg border border-white/10">
                            ✨ Gratis
                        </span>
                        dan jangkau ribuan pencari kos setiap hari.
                    </p>

                    {{-- Feature pills --}}
                    <div class="flex flex-wrap justify-center lg:justify-start gap-2 mb-10">
                        @foreach(['Tanpa Biaya Admin', 'Langsung Online', 'Ribuan Pengunjung', 'Chat Langsung'] as $feature)
                            <span class="text-xs font-semibold text-slate-300 bg-slate-800/60 border border-slate-700/50 px-3.5 py-1.5 rounded-full">
                                ✓ {{ $feature }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- CTA Button Area --}}
                <div class="shrink-0 text-center">
                    <a href="{{ route('kos.create') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-bold px-10 py-5 rounded-2xl transition-all duration-300 text-lg shadow-[0_20px_40px_-10px_rgba(16,185,129,0.4)] hover:shadow-[0_25px_50px_-10px_rgba(16,185,129,0.5)] hover:-translate-y-1 active:translate-y-0">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Pasang Kos Sekarang</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <p class="text-slate-500 text-sm mt-4 font-medium">
                        Gratis selamanya · Tanpa kartu kredit
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════ --}}
{{--  FAQ – Accordion Style                          --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="py-24 bg-white" id="faq">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="reveal text-center mb-16">
            <div class="inline-flex items-center gap-2 text-brand-600 bg-brand-50 px-4 py-1.5 rounded-full text-xs font-bold mb-4 tracking-wide uppercase shadow-sm border border-brand-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                FAQ
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Pertanyaan Umum</h2>
            <p class="text-slate-500 text-lg font-medium">Yang sering ditanyakan tentang BaKos.</p>
        </div>

        <div class="reveal space-y-4" x-data="{ open: null }">
            @foreach([
                ['Apakah BaKos gratis?', 'Ya, 100% gratis! Baik untuk pencari kos maupun pemilik kos. Tidak ada biaya tersembunyi, biaya admin, atau komisi apapun.'],
                ['Bagaimana cara pasang iklan kos?', 'Cukup klik tombol "Pasang Kos Sekarang", isi detail properti Anda, upload foto, dan iklan akan langsung tayang setelah diverifikasi.'],
                ['Apakah foto kos di BaKos asli?', 'Kami mendorong semua pemilik kos untuk mengupload foto asli. Tim kami melakukan verifikasi untuk memastikan kualitas dan keaslian konten.'],
                ['Bagaimana cara menghubungi pemilik kos?', 'Setiap listing kos memiliki tombol WhatsApp yang langsung terhubung ke nomor pemilik kos. Tanpa perantara!'],
                ['Apakah BaKos hanya untuk area Pandu?', 'Saat ini kami secara khusus memfokuskan pencarian kos untuk area Pandu dan sekitarnya. Kami terus berkembang dan akan segera hadir di area-area lainnya.'],
            ] as $index => [$question, $answer])
                <div class="group border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-300 hover:border-slate-300/80"
                     :class="{ 'bg-slate-50 border-brand-200/50 shadow-lg shadow-brand-50': open === {{ $index }}, 'bg-white': open !== {{ $index }} }">
                    <button @click="open = open === {{ $index }} ? null : {{ $index }}" class="w-full flex items-center justify-between px-7 py-5 text-left transition-colors duration-200">
                        <span class="font-bold text-slate-900 pr-4" :class="{ 'text-brand-700': open === {{ $index }} }">
                            {{ $question }}
                        </span>
                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300"
                             :class="{ 'bg-brand-100 text-brand-600 rotate-180': open === {{ $index }}, 'bg-slate-100 text-slate-400': open !== {{ $index }} }">
                            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="open === {{ $index }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-1 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-1 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         x-cloak>
                        <div class="px-7 pb-6 text-slate-500 leading-relaxed font-medium">
                            {{ $answer }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════ --}}
{{--  FINAL CTA – Newsletter / Bottom CTA            --}}
{{-- ═══════════════════════════════════════════════ --}}
<section class="py-24 bg-gradient-to-b from-white to-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="reveal text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-brand-100 to-brand-200 rounded-3xl mx-auto mb-8 flex items-center justify-center shadow-lg shadow-brand-100/40 animate-float">
                <svg class="w-10 h-10 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-5">
                Siap cari kos <span class="text-gradient-animate">impianmu?</span>
            </h2>
            <p class="text-slate-500 text-lg font-medium mb-10 max-w-xl mx-auto">
                Era baru mencari tempat tinggal telah tiba. Awali pencarian hunian nyamanmu dengan BaKos.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('kos.index') }}" class="group inline-flex items-center gap-2.5 bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-300 shadow-[0_10px_25px_-8px_rgba(16,185,129,0.4)] hover:shadow-[0_15px_30px_-8px_rgba(16,185,129,0.5)] hover:-translate-y-1 text-lg">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Mulai Cari Kos
                </a>
                
                {{-- Tampilkan tombol ini HANYA JIKA belum login, ATAU jika login sebagai pemilik/admin --}}
                @if(!auth()->check() || (auth()->user()?->isPemilik() || auth()->user()?->isAdmin()))
                <a href="{{ route('kos.create') }}" class="group inline-flex items-center gap-2.5 bg-white text-slate-700 font-bold px-8 py-4 rounded-2xl border border-slate-200 hover:border-slate-300 transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1 text-lg">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Pasang Kos
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // ═══ Scroll Reveal Observer ═══
    document.addEventListener('DOMContentLoaded', () => {
        const reveals = document.querySelectorAll('.reveal');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        reveals.forEach(el => observer.observe(el));
    });

    // ═══ Smooth Parallax on Hero Blobs ═══
    document.addEventListener('mousemove', (e) => {
        const hero = document.getElementById('hero');
        if (!hero) return;

        const rect = hero.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > window.innerHeight) return;

        const x = (e.clientX / window.innerWidth - 0.5) * 20;
        const y = (e.clientY / window.innerHeight - 0.5) * 20;

        hero.querySelectorAll('.animate-float, .animate-float-reverse, .animate-float-slow').forEach((el, i) => {
            const speed = (i + 1) * 0.3;
            el.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
        });
    });

    // ═══ Navbar Background on Scroll ═══
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('nav');
        if (!nav) return;

        if (window.scrollY > 50) {
            nav.classList.add('bg-white/80', 'backdrop-blur-xl', 'shadow-sm', 'border-b', 'border-slate-100');
        } else {
            nav.classList.remove('bg-white/80', 'backdrop-blur-xl', 'shadow-sm', 'border-b', 'border-slate-100');
        }
    });
</script>
@endpush