@extends('layouts.app')

@section('title', 'Cari Kos - BaKos')

@push('head')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@section('content')

{{-- ── Search & Filter Bar (Sticky) ── --}}
<div class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-20 z-40 shadow-sm">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <form action="{{ route('kos.index') }}" method="GET"
              class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center"
              id="search-form">

            {{-- Search Input --}}
            <div class="relative flex-1 min-w-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama kos, jalan, atau fasilitas..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:bg-white focus:border-brand-400 transition-all">
            </div>

            {{-- Filter Row --}}
            <div class="flex gap-2 flex-wrap sm:flex-nowrap">
                {{-- Jenis --}}
                <select name="jenis" onchange="this.form.submit()"
                        class="flex-1 sm:w-36 py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer transition-all appearance-none">
                    <option value="">Semua Tipe</option>
                    <option value="putra" {{ request('jenis') == 'putra' ? 'selected' : '' }}>🙋‍♂️ Putra</option>
                    <option value="putri" {{ request('jenis') == 'putri' ? 'selected' : '' }}>🙋‍♀️ Putri</option>
                    <option value="campur" {{ request('jenis') == 'campur' ? 'selected' : '' }}>👥 Campur</option>
                </select>

                {{-- Kota --}}
                <select name="kota" onchange="this.form.submit()"
                        class="flex-1 sm:w-36 py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer transition-all appearance-none">
                    <option value="">Semua Kota</option>
                    <option value="Manado" {{ request('kota') == 'Manado' ? 'selected' : '' }}>📍 Manado</option>
                    <option value="Minahasa" {{ request('kota') == 'Minahasa' ? 'selected' : '' }}>📍 Minahasa</option>
                </select>

                {{-- Harga Max --}}
                <select name="harga_max" onchange="this.form.submit()"
                        class="flex-1 sm:w-40 py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 cursor-pointer transition-all appearance-none">
                    <option value="">Semua Harga</option>
                    <option value="500000" {{ request('harga_max') == '500000' ? 'selected' : '' }}>💰 ≤ Rp 500K</option>
                    <option value="1000000" {{ request('harga_max') == '1000000' ? 'selected' : '' }}>💰 ≤ Rp 1 Juta</option>
                    <option value="1500000" {{ request('harga_max') == '1500000' ? 'selected' : '' }}>💰 ≤ Rp 1,5 Juta</option>
                    <option value="2000000" {{ request('harga_max') == '2000000' ? 'selected' : '' }}>💰 ≤ Rp 2 Juta</option>
                </select>

                {{-- Search Button --}}
                <button type="submit"
                        class="bg-brand-600 hover:bg-brand-700 active:scale-95 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm shadow-brand-500/20 flex items-center gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="hidden sm:inline">Cari</span>
                </button>

                {{-- Toggle Map (Mobile) --}}
                <button type="button" id="toggle-map-btn"
                        class="lg:hidden bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Peta
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Result Count ── --}}
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-3">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">
                {{ request('q') ? '"'.request('q').'"' : 'Semua Kos Tersedia' }}
            </h1>
            <p class="text-slate-500 text-sm mt-0.5">
                <span class="font-bold text-brand-600">{{ $kosList->total() }}</span> kos ditemukan
                @if(request()->hasAny(['q','jenis','kota','harga_max']))
                    · <a href="{{ route('kos.index') }}" class="text-slate-400 hover:text-rose-500 transition-colors underline underline-offset-2 text-xs">Reset filter</a>
                @endif
            </p>
        </div>
    </div>
</div>

{{-- ── Split-Screen: List + Map ── --}}
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="flex gap-6 relative">

        {{-- LEFT: Kos List --}}
        <div class="flex-1 min-w-0">
            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @forelse($kosList as $kos)
                    <x-kos-card :kos="$kos"/>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Kos tidak ditemukan</h3>
                        <p class="text-slate-500 text-sm max-w-sm mt-1">Coba ubah kata kunci atau sesuaikan filter Anda.</p>
                        <a href="{{ route('kos.index') }}" class="mt-6 bg-brand-600 text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:bg-brand-700 transition-colors">Reset Pencarian</a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $kosList->withQueryString()->links() }}
            </div>
        </div>

        {{-- RIGHT: Map Panel (Desktop Sticky) --}}
        <div class="hidden lg:block w-[420px] shrink-0">
            <div class="sticky top-36 h-[calc(100vh-10rem)] rounded-2xl overflow-hidden border border-slate-200 shadow-lg">
                <div id="search-map" class="w-full h-full z-0"></div>
            </div>
        </div>

        {{-- Mobile Map Panel (toggle) --}}
        <div id="mobile-map-panel" class="hidden lg:hidden fixed inset-x-0 bottom-20 z-50 mx-4 h-72 rounded-2xl overflow-hidden border border-slate-200 shadow-2xl">
            <div id="mobile-search-map" class="w-full h-full z-0"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Map data (pre-processed from controller)
    const kosData = {!! $mapData !!};

    function initMap(containerId) {
        if (!document.getElementById(containerId)) return null;

        const map = L.map(containerId, {
            scrollWheelZoom: false,
            zoomControl: true,
        }).setView([1.4748, 124.8421], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        kosData.forEach(kos => {
            const color = kos.available ? '#10b981' : '#f43f5e';
            const icon = L.divIcon({
                html: `<div style="background:${color};width:36px;height:36px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">
                           <span style="transform:rotate(45deg);font-size:14px;">🏠</span>
                       </div>`,
                className: '',
                iconSize: [36, 36],
                iconAnchor: [18, 36],
                popupAnchor: [0, -38],
            });

            L.marker([kos.lat, kos.lng], { icon })
                .addTo(map)
                .bindPopup(`
                    <div style="min-width:160px;font-family:sans-serif;">
                        <p style="font-weight:700;font-size:13px;margin:0 0 4px">${kos.nama}</p>
                        <p style="font-size:12px;color:#059669;font-weight:700;margin:0 0 4px">${kos.harga}<span style="color:#94a3b8;font-weight:400">/bln</span></p>
                        <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:${kos.available ? '#d1fae5' : '#ffe4e6'};color:${kos.available ? '#065f46' : '#9f1239'}">
                            ${kos.available ? '● Tersedia' : '● Penuh'}
                        </span>
                        <br><a href="${kos.url}" style="display:inline-block;margin-top:8px;font-size:11px;font-weight:700;color:#2563eb;text-decoration:none;">Lihat Detail →</a>
                    </div>
                `);
        });

        // Fit bounds if data exists
        if (kosData.length > 0) {
            const bounds = kosData.map(k => [k.lat, k.lng]);
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 15 });
        }

        return map;
    }

    // Init desktop map
    initMap('search-map');

    // Mobile map toggle
    const toggleBtn = document.getElementById('toggle-map-btn');
    const mobilePanel = document.getElementById('mobile-map-panel');
    let mobileMapInitialized = false;

    if (toggleBtn && mobilePanel) {
        toggleBtn.addEventListener('click', function () {
            const isHidden = mobilePanel.classList.toggle('hidden');
            if (!isHidden && !mobileMapInitialized) {
                setTimeout(() => initMap('mobile-search-map'), 100);
                mobileMapInitialized = true;
            }
        });
    }
});
</script>
@endpush