{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BaKos') – Cari Kos Tanpa Ribet</title>

    {{-- Preconnect & Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4',
                            300: '#5eead4', 400: '#2dd4bf', 500: '#14b8a6',
                            600: '#0d9488', 700: '#0f766e', 800: '#115e59', 900: '#134e4a',
                        },
                        surface: '#ffffff',
                        background: '#f8fafc',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'float': '0 8px 30px rgba(0, 0, 0, 0.08)',
                        'glow': '0 0 30px -5px rgba(20, 184, 166, 0.3)',
                        'inner-soft': 'inset 0 2px 4px rgba(0, 0, 0, 0.04)',
                    },
                    keyframes: {
                        'fade-in-down': {
                            '0%': { opacity: '0', transform: 'translateY(-8px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'fade-in-up': {
                            '0%': { opacity: '0', transform: 'translateY(12px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'slide-in-right': {
                            '0%': { opacity: '0', transform: 'translateX(20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        },
                        'scale-in': {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-8px)' },
                        },
                        'blob': {
                            '0%, 100%': { borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%' },
                            '25%': { borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%' },
                            '50%': { borderRadius: '50% 60% 30% 60% / 30% 60% 70% 40%' },
                            '75%': { borderRadius: '60% 40% 60% 30% / 60% 40% 30% 70%' },
                        },
                        'shimmer': {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        },
                    },
                    animation: {
                        'fade-in-down': 'fade-in-down 0.4s ease-out',
                        'fade-in-up': 'fade-in-up 0.5s ease-out',
                        'slide-in-right': 'slide-in-right 0.4s ease-out',
                        'scale-in': 'scale-in 0.3s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'float-slow': 'float 10s ease-in-out infinite',
                        'blob': 'blob 8s ease-in-out infinite',
                        'blob-slow': 'blob 12s ease-in-out infinite',
                        'shimmer': 'shimmer 2s infinite',
                    },
                }
            }
        }
    </script>

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Stack: Page-specific styles --}}
    @stack('styles')

    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #f8fafc; }

        /* ═══ Global Utilities ═══ */

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }
        .glass-strong {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
        }
        .glass-dark {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Animated gradient text */
        .text-gradient-animate {
            background: linear-gradient(135deg, #14b8a6, #0d9488, #06b6d4, #14b8a6);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 4s ease infinite;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Noise texture */
        .noise::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }

        /* Smooth custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
            border: 2px solid #f1f5f9;
        }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Dropdown menu items active state */
        .nav-dropdown-item {
            position: relative;
            overflow: hidden;
        }
        .nav-dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 60%;
            background: linear-gradient(to bottom, #14b8a6, #0d9488);
            border-radius: 0 4px 4px 0;
            transition: width 0.2s ease;
        }
        .nav-dropdown-item:hover::before {
            width: 3px;
        }

        /* Mobile menu overlay */
        .mobile-menu-backdrop {
            background: rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(4px);
        }

        /* Button press effect */
        .btn-press {
            transition: all 0.15s ease;
        }
        .btn-press:active {
            transform: scale(0.97);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800">

    {{-- ═══════════════════════════════════════════ --}}
    {{--  NAVBAR – Refined Glassmorphism              --}}
    {{-- ═══════════════════════════════════════════ --}}
    <nav x-data="{
            mobileOpen: false,
            scrolled: false,
            profileOpen: false,
         }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         @keydown.escape.window="mobileOpen = false; profileOpen = false"
         :class="scrolled
            ? 'glass-strong border-slate-200/60 shadow-[0_4px_30px_-4px_rgba(0,0,0,0.06)]'
            : 'bg-white/60 backdrop-blur-md border-transparent'"
         class="fixed top-0 inset-x-0 z-50 border-b transition-all duration-500">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                {{-- ── Logo ── --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 group relative">
                    {{-- Logo Icon with hover glow --}}
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand-400/20 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative w-10 h-10 bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-slate-300/30 group-hover:rounded-2xl">
                            <svg class="w-5 h-5 text-white transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-0.5">
                        <span class="text-2xl font-extrabold tracking-tight text-slate-900 transition-colors group-hover:text-slate-700">Ba</span>
                        <span class="text-2xl font-extrabold tracking-tight text-brand-600 transition-colors group-hover:text-brand-500">Kos</span>
                        <span class="text-brand-500 text-2xl font-extrabold leading-none animate-pulse"></span>
                    </div>
                </a>

                {{-- ── Desktop Navigation ── --}}
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $navItems = [
                            ['label' => 'Cari Kos', 'route' => route('kos.index'), 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                            ['label' => 'Manado', 'route' => route('kos.index', ['kota'=>'Manado']), 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                            ['label' => 'Bunaken', 'route' => route('kos.index', ['q'=>'Bunaken']), 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                        ];
                    @endphp

                    @foreach($navItems as $nav)
                        <a href="{{ $nav['route'] }}"
                           class="group relative flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-900 px-4 py-2.5 rounded-xl transition-all duration-200 hover:bg-slate-50/80">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"/>
                            </svg>
                            {{ $nav['label'] }}
                            {{-- Hover underline indicator --}}
                            <span class="absolute bottom-1 left-4 right-4 h-0.5 bg-brand-500 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                        </a>
                    @endforeach
                </div>

                {{-- ── Auth Area (Desktop) ── --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        {{-- Pasang Kos Quick Action (Sembunyikan dari Pencari Kos) --}}
                        @if(auth()->user()->isPemilik() || auth()->user()->isAdmin())
                        <a href="{{ route('kos.create') }}"
                           class="btn-press inline-flex items-center gap-2 text-sm font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 px-4 py-2.5 rounded-xl border border-brand-100 hover:border-brand-200 transition-all duration-200 hover:shadow-md hover:shadow-brand-100/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span class="hidden lg:inline">Pasang Kos</span>
                        </a>
                        @endif

                        {{-- Profile Dropdown --}}
                        <div class="relative" @click.outside="profileOpen = false">
                            <button @click="profileOpen = !profileOpen"
                                    class="btn-press flex items-center gap-2.5 hover:bg-slate-50/80 pl-2 pr-3 py-1.5 rounded-2xl transition-all duration-200 border border-transparent hover:border-slate-200/80 group"
                                    :class="profileOpen ? 'bg-slate-50 border-slate-200/80 shadow-sm' : ''">

                                {{-- Avatar with online indicator --}}
                                <div class="relative">
                                    <img src="{{ auth()->user()->foto_profil_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=ccfbf1&color=0f766e&bold=true' }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="w-9 h-9 rounded-xl object-cover border-2 border-brand-100 group-hover:border-brand-200 transition-colors shadow-sm">
                                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-white shadow-sm"></div>
                                </div>

                                <div class="text-left hidden lg:block">
                                    <p class="text-sm font-bold text-slate-900 leading-tight">{{ Str::limit(auth()->user()->name, 14) }}</p>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ auth()->user()->role_label ?? ucfirst(auth()->user()->role) }}</p>
                                </div>

                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                     :class="profileOpen ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="profileOpen"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-1 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-1 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                 class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_20px_50px_-15px_rgba(0,0,0,0.15)] border border-slate-100/80 py-2 z-50 overflow-hidden">

                                {{-- User Info Header --}}
                                <div class="px-4 py-3 mb-1 bg-gradient-to-r from-slate-50 to-brand-50/30 border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ auth()->user()->foto_profil_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=ccfbf1&color=0f766e&bold=true' }}"
                                             class="w-10 h-10 rounded-xl object-cover border border-brand-100 shadow-sm">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Menu Items --}}
                                <div class="py-1">
                                    <a href="{{ route('profile.show') }}" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600 transition-all duration-200 group/item">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover/item:bg-brand-50 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-slate-400 group-hover/item:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        Profil Saya
                                    </a>

                                    <a href="{{ route('favorites.index') }}" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600 transition-all duration-200 group/item">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover/item:bg-rose-50 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-slate-400 group-hover/item:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        </div>
                                        Kos Favorit
                                    </a>

                                    @if(auth()->user()->role === 'pemilik')
                                    <a href="{{ route('pemilik.dashboard') }}" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600 transition-all duration-200 group/item">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover/item:bg-brand-50 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-slate-400 group-hover/item:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                                        </div>
                                        Dashboard Pemilik
                                    </a>
                                    @endif

                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="nav-dropdown-item flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-amber-700 hover:bg-amber-50 transition-all duration-200 group/item">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        Panel Admin
                                    </a>
                                    @endif
                                </div>

                                {{-- Pasang Kos Dropdown (Sembunyikan dari Pencari Kos) --}}
                                @if(auth()->user()->isPemilik() || auth()->user()->isAdmin())
                                <div class="my-1 mx-4 border-t border-slate-100"></div>
                                <div class="px-3 py-1">
                                    <a href="{{ route('kos.create') }}"
                                       class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-xl transition-all duration-200 group/item">
                                        <div class="w-8 h-8 rounded-lg bg-brand-100 group-hover/item:bg-brand-200 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        Pasang Kos Baru
                                    </a>
                                </div>
                                @endif

                                <div class="my-1 mx-4 border-t border-slate-100"></div>

                                {{-- Logout --}}
                                <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-200 group/item">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 group-hover/item:bg-rose-100 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-rose-400 group-hover/item:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        </div>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Guest Actions --}}
                        <a href="{{ route('login') }}"
                           class="btn-press text-sm font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl hover:bg-slate-50 transition-all duration-200">
                            Masuk
                        </a>
                        {{-- Prominent Pasang Kos CTA for guests --}}
                        <a href="{{ route('register') }}"
                           class="btn-press group relative inline-flex items-center gap-2 bg-gradient-to-r from-brand-500 to-brand-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow-md shadow-brand-500/30 hover:shadow-lg hover:shadow-brand-500/40 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                            <svg class="relative w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span class="relative">Pasang Kos Anda</span>
                        </a>
                    @endauth
                </div>

                {{-- ── Mobile Toggle ── --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden btn-press relative w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                    <div class="w-5 h-4 flex flex-col justify-between items-center">
                        <span class="block w-5 h-0.5 bg-current rounded-full transition-all duration-300"
                              :class="mobileOpen ? 'rotate-45 translate-y-[7px]' : ''"></span>
                        <span class="block w-5 h-0.5 bg-current rounded-full transition-all duration-200"
                              :class="mobileOpen ? 'opacity-0 scale-x-0' : ''"></span>
                        <span class="block w-5 h-0.5 bg-current rounded-full transition-all duration-300"
                              :class="mobileOpen ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                    </div>
                </button>
            </div>
        </div>

        {{-- ── Mobile Menu (Slide Down) ── --}}
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-1 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-1 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.outside="mobileOpen = false"
             class="md:hidden absolute w-full glass-strong border-t border-slate-100/80 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.1)]">

            <div class="max-w-lg mx-auto px-4 py-6 space-y-1">

                {{-- Nav Links --}}
                @foreach($navItems as $index => $nav)
                    <a href="{{ $nav['route'] }}"
                       class="flex items-center gap-3 px-4 py-3.5 text-sm font-bold text-slate-700 hover:bg-brand-50 hover:text-brand-700 rounded-xl transition-all duration-200"
                       style="animation: fade-in-up 0.3s ease-out {{ ($index + 1) * 0.05 }}s both;">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"/>
                            </svg>
                        </div>
                        {{ $nav['label'] }}
                    </a>
                @endforeach

                @auth
                    {{-- Divider --}}
                    <div class="py-2"><div class="border-t border-slate-200/60"></div></div>

                    {{-- User Card --}}
                    <div class="flex items-center gap-3 px-4 py-4 bg-gradient-to-r from-slate-50 to-brand-50/30 rounded-2xl border border-slate-100">
                        <div class="relative">
                            <img src="{{ auth()->user()->foto_profil_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=ccfbf1&color=0f766e&bold=true' }}"
                                 class="w-12 h-12 rounded-xl object-cover border-2 border-brand-100 shadow-sm">
                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 rounded-full border-2 border-white"></div>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-slate-900 text-sm truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs font-medium text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600 rounded-xl transition-all">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                        Profil Saya
                    </a>
                    <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600 rounded-xl transition-all">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></div>
                        Kos Favorit
                    </a>

                    @if(auth()->user()->role === 'pemilik')
                    <a href="{{ route('pemilik.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600 rounded-xl transition-all">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center"><svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg></div>
                        Dashboard Pemilik
                    </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-amber-700 hover:bg-amber-50 rounded-xl transition-all">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                        Panel Admin
                    </a>
                    @endif

                    <div class="py-2"><div class="border-t border-slate-200/60"></div></div>

                    {{-- Pasang Kos CTA (Mobile) - Sembunyikan dari Pencari Kos --}}
                    @if(auth()->user()->isPemilik() || auth()->user()->isAdmin())
                    <a href="{{ route('kos.create') }}"
                       class="flex items-center justify-center gap-2 px-4 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-200/40 transition-all hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Pasang Kos Baru
                    </a>
                    @endif

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="py-2"><div class="border-t border-slate-200/60"></div></div>

                    <a href="{{ route('login') }}"
                       class="block text-center px-4 py-3.5 text-sm font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 transition-all">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center justify-center gap-2 text-center px-4 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-brand-500 to-brand-600 rounded-xl shadow-md shadow-brand-500/20 mt-2 transition-all hover:shadow-lg hover:shadow-brand-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Pasang Kos Anda
                    </a>
                @endauth
            </div>
        </div>
    </nav>


    {{-- ═══════════════════════════════════════════ --}}
    {{--  FLASH MESSAGES – Floating Toast               --}}
    {{-- ═══════════════════════════════════════════ --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 translate-x-8 scale-95"
         x-transition:enter-end="opacity-1 translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-1 translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 translate-x-8 scale-95"
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-24 right-4 z-[60] max-w-sm">
        <div class="bg-white border border-emerald-200 rounded-2xl shadow-[0_20px_40px_-10px_rgba(16,185,129,0.2)] p-4 flex items-start gap-3">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900">Berhasil!</p>
                <p class="text-sm text-slate-500 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 transition-colors text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            {{-- Progress bar --}}
            <div class="absolute bottom-0 left-4 right-4 h-0.5 bg-emerald-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-400 rounded-full" style="animation: shrink 5s linear forwards;"></div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 translate-x-8 scale-95"
         x-transition:enter-end="opacity-1 translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-1 translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 translate-x-8 scale-95"
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-24 right-4 z-[60] max-w-sm">
        <div class="bg-white border border-rose-200 rounded-2xl shadow-[0_20px_40px_-10px_rgba(244,63,94,0.15)] p-4 flex items-start gap-3">
            <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900">Oops!</p>
                <p class="text-sm text-slate-500 mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 transition-colors text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="absolute bottom-0 left-4 right-4 h-0.5 bg-rose-100 rounded-full overflow-hidden">
                <div class="h-full bg-rose-400 rounded-full" style="animation: shrink 5s linear forwards;"></div>
            </div>
        </div>
    </div>
    @endif


    {{-- ═══════════════════════════════════════════ --}}
    {{--  MAIN CONTENT                                  --}}
    {{-- ═══════════════════════════════════════════ --}}
    <main class="pt-20 min-h-screen">
        @yield('content')
    </main>


    {{-- ═══════════════════════════════════════════ --}}
    {{--  FOOTER – Refined & Modern                     --}}
    {{-- ═══════════════════════════════════════════ --}}
    <footer class="relative bg-white border-t border-slate-100 mt-24 overflow-hidden">
        {{-- Subtle background decoration --}}
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-brand-50/40 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-slate-100/50 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-12">

            {{-- Footer Top: CTA Strip --}}
            <div class="reveal flex flex-col sm:flex-row items-center justify-between gap-6 bg-gradient-to-r from-slate-50 to-brand-50/40 rounded-3xl p-8 mb-16 border border-slate-100">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">Siap menemukan kos impianmu?</h3>
                    <p class="text-slate-500 mt-1 font-medium text-sm">Mulai jelajahi berbagai pilihan kos terbaik sekarang juga..</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('kos.index') }}"
                       class="btn-press bg-gradient-to-r from-brand-500 to-brand-600 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-md shadow-brand-200/40 hover:shadow-lg transition-all">
                        Cari Kos Sekarang
                    </a>

                    {{-- Pasang Kos Footer Button (Sembunyikan dari Pencari Kos) --}}
                    @if(!auth()->check() || (auth()->check() && (auth()->user()->isPemilik() || auth()->user()->isAdmin())))
                    <a href="{{ route('kos.create') }}"
                       class="btn-press bg-white text-slate-700 font-bold px-6 py-3 rounded-xl text-sm border border-slate-200 hover:border-slate-300 shadow-sm hover:shadow-md transition-all">
                        Pasang Kos
                    </a>
                    @endif
                </div>
            </div>

            {{-- Footer Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-10 md:gap-8">

                {{-- Brand Column --}}
                <div class="col-span-2 md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-10 h-10 bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold text-slate-900">BaKos</span>
                            <span class="text-xl font-extrabold text-brand-500">.</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium mb-6 max-w-xs">
                        Platform terpercaya untuk menemukan kos nyaman di Manado & Sulawesi Utara. Tanpa ribet, tanpa biaya.
                    </p>

                    {{-- Social Links --}}
                    <div class="flex items-center gap-2">
                        @foreach([
                            ['Instagram', 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'],
                            ['Twitter', 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z'],
                        ] as [$name, $path])
                            <a href="#" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-brand-50 flex items-center justify-center text-slate-400 hover:text-brand-600 transition-all duration-200 hover:-translate-y-0.5" title="{{ $name }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Area Kos --}}
                <div>
                    <h4 class="text-slate-900 font-bold mb-5 text-xs uppercase tracking-widest">Area Kos</h4>
                    <ul class="space-y-3">
                        @foreach(['Rs Universitas Manado','Universitas Muhamadiyah', 'Amik Manado', 'Puskesmas Pandu', 'Kantor kelurahan Pandu',] as $kec)
                        <li>
                            <a href="{{ route('kos.index', ['kota'=>'Manado','q'=>$kec]) }}"
                               class="text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors duration-200 inline-flex items-center gap-1 group/link">
                                <span class="w-0 group-hover/link:w-2 h-0.5 bg-brand-400 rounded-full transition-all duration-200"></span>
                                 {{ $kec }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Akses Cepat --}}
                <div>
                    <h4 class="text-slate-900 font-bold mb-5 text-xs uppercase tracking-widest">Akses Cepat</h4>
                    <ul class="space-y-3">
                        @auth
                            {{-- Menu akses cepat jika sudah login --}}
                            <li>
                                <a href="{{ route('profile.show') }}" class="text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors duration-200 inline-flex items-center gap-1 group/link">
                                    <span class="w-0 group-hover/link:w-2 h-0.5 bg-brand-400 rounded-full transition-all duration-200"></span>
                                    Profil Saya
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('favorites.index') }}" class="text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors duration-200 inline-flex items-center gap-1 group/link">
                                    <span class="w-0 group-hover/link:w-2 h-0.5 bg-brand-400 rounded-full transition-all duration-200"></span>
                                    Kos Favorit
                                </a>
                            </li>
                            {{-- Sembunyikan "Pasang Iklan" dari Pencari Kos --}}
                            @if(auth()->user()->isPemilik() || auth()->user()->isAdmin())
                            <li>
                                <a href="{{ route('kos.create') }}" class="text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors duration-200 inline-flex items-center gap-1 group/link">
                                    <span class="w-0 group-hover/link:w-2 h-0.5 bg-brand-400 rounded-full transition-all duration-200"></span>
                                    Pasang Iklan
                                </a>
                            </li>
                            @endif
                        @else
                            {{-- Menu akses cepat jika belum login --}}
                            @foreach([
                                ['Masuk Akun', route('login')],
                                ['Daftar Gratis', route('register')],
                                ['Pasang Kos', route('kos.create')],
                            ] as [$label, $url])
                            <li>
                                <a href="{{ $url }}" class="text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors duration-200 inline-flex items-center gap-1 group/link">
                                    <span class="w-0 group-hover/link:w-2 h-0.5 bg-brand-400 rounded-full transition-all duration-200"></span>
                                    {{ $label }}
                                </a>
                            </li>
                            @endforeach
                        @endauth
                    </ul>
                </div>

                {{-- Bantuan --}}
                <div>
                    <h4 class="text-slate-900 font-bold mb-5 text-xs uppercase tracking-widest">Bantuan</h4>
                    <ul class="space-y-3">
                        @foreach(['Tentang Kami', 'Pusat Bantuan', 'Syarat & Ketentuan', 'Kebijakan Privasi'] as $item)
                        <li>
                            <a href="#" class="text-sm text-slate-500 hover:text-brand-600 font-medium transition-colors duration-200 inline-flex items-center gap-1 group/link">
                                <span class="w-0 group-hover/link:w-2 h-0.5 bg-brand-400 rounded-full transition-all duration-200"></span>
                                {{ $item }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Footer Bottom --}}
            <div class="border-t border-slate-100 mt-16 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-slate-400 font-medium">
                        © {{ date('Y') }} BaKos. Hak cipta dilindungi undang-undang.
                    </p>
                    <div class="flex items-center gap-2 text-sm text-slate-400 font-medium">
                        <span>Dibuat oleh Mahasiswa Amik Manado</span>
                    {{--   <span>di Manado, Sulawesi Utara</span> !--> --}}
                    </div>
                </div>
            </div>
        </div>
    </footer>


    {{-- ═══════════════════════════════════════════ --}}
    {{--  SCROLL TO TOP BUTTON                          --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-data="{ show: false }"
         @scroll.window="show = (window.pageYOffset > 500)"
         x-show="show"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-90"
         x-transition:enter-end="opacity-1 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-1 translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-90"
         class="fixed bottom-6 right-6 z-50">
        <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                class="btn-press w-12 h-12 bg-white border border-slate-200 rounded-2xl shadow-float hover:shadow-lg flex items-center justify-center text-slate-500 hover:text-brand-600 hover:border-brand-200 transition-all duration-300 hover:-translate-y-1 group">
            <svg class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>


    {{-- ═══════════════════════════════════════════ --}}
    {{--  SCRIPTS                                       --}}
    {{-- ═══════════════════════════════════════════ --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ═══ Scroll Reveal Observer ═══
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');
            if (reveals.length === 0) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.08,
                rootMargin: '0px 0px -40px 0px'
            });

            reveals.forEach(el => observer.observe(el));
        });
    </script>

    {{-- Toast progress bar animation --}}
    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    @stack('scripts')

    {{-- ═══════════════════════════════════════════ --}}
    {{--  MOBILE BOTTOM NAVIGATION BAR               --}}
    {{-- ═══════════════════════════════════════════ --}}
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur-xl border-t border-slate-200 shadow-[0_-4px_20px_-4px_rgba(0,0,0,0.08)]">
        <div class="flex items-center justify-around px-2 py-2 max-w-sm mx-auto">

            {{-- Beranda --}}
            <a href="{{ route('home') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-1.5 rounded-xl transition-all group
                      {{ request()->routeIs('home') ? 'text-brand-600' : 'text-slate-400 hover:text-slate-700' }}">
                <div class="w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold">Beranda</span>
                @if(request()->routeIs('home'))
                    <span class="w-1 h-1 rounded-full bg-brand-500"></span>
                @endif
            </a>

            {{-- Cari --}}
            <a href="{{ route('kos.index') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-1.5 rounded-xl transition-all group
                      {{ request()->routeIs('kos.index') ? 'text-brand-600' : 'text-slate-400 hover:text-slate-700' }}">
                <div class="w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('kos.index') ? '2.5' : '2' }}" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold">Cari</span>
                @if(request()->routeIs('kos.index'))
                    <span class="w-1 h-1 rounded-full bg-brand-500"></span>
                @endif
            </a>

            {{-- CTA Pasang Kos (tengah, menonjol) --}}
            @auth
                @if(auth()->user()->isPemilik() || auth()->user()->isAdmin())
                <a href="{{ route('kos.create') }}"
                   class="flex flex-col items-center gap-0.5 -mt-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/40 active:scale-95 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-brand-600 mt-0.5">Pasang</span>
                </a>
                @else
                <a href="{{ route('kos.index') }}"
                   class="flex flex-col items-center gap-0.5 -mt-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/40 active:scale-95 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-brand-600 mt-0.5">Jelajahi</span>
                </a>
                @endif
            @else
                <a href="{{ route('register') }}"
                   class="flex flex-col items-center gap-0.5 -mt-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/40 active:scale-95 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-brand-600 mt-0.5">Pasang</span>
                </a>
            @endauth

            {{-- Favorit --}}
            @auth
            <a href="{{ route('favorites.index') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-1.5 rounded-xl transition-all
                      {{ request()->routeIs('favorites.*') ? 'text-rose-500' : 'text-slate-400 hover:text-slate-700' }}">
                <div class="w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('favorites.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold">Favorit</span>
                @if(request()->routeIs('favorites.*'))
                    <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                @endif
            </a>
            @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-1.5 rounded-xl transition-all text-slate-400 hover:text-slate-700">
                <div class="w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold">Favorit</span>
            </a>
            @endauth

            {{-- Profil --}}
            @auth
            <a href="{{ route('profile.show') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-1.5 rounded-xl transition-all
                      {{ request()->routeIs('profile.*') ? 'text-brand-600' : 'text-slate-400 hover:text-slate-700' }}">
                <div class="w-6 h-6 flex items-center justify-center">
                    <img src="{{ auth()->user()->foto_profil_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=ccfbf1&color=0f766e&bold=true' }}"
                         class="w-6 h-6 rounded-full object-cover border-2 {{ request()->routeIs('profile.*') ? 'border-brand-500' : 'border-slate-300' }} transition-colors">
                </div>
                <span class="text-[10px] font-bold">Profil</span>
                @if(request()->routeIs('profile.*'))
                    <span class="w-1 h-1 rounded-full bg-brand-500"></span>
                @endif
            </a>
            @else
            <a href="{{ route('login') }}"
               class="flex flex-col items-center gap-0.5 px-4 py-1.5 rounded-xl transition-all text-slate-400 hover:text-slate-700">
                <div class="w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-[10px] font-bold">Masuk</span>
            </a>
            @endauth
        </div>
    </nav>

    {{-- Bottom padding spacer for mobile to prevent content hiding behind bottom nav --}}
    <div class="h-20 md:hidden"></div>

</body>
</html>