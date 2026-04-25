{{-- resources/views/components/kos-card.blade.php --}}
@props(['kos', 'compact' => false])

@php
    // Build the full list of photos for the carousel
    $allPhotos = [$kos->foto_utama_url];
    if (!empty($kos->foto_tambahan) && is_array($kos->foto_tambahan)) {
        foreach ($kos->foto_tambahan as $foto) {
            if (is_string($foto) && str_contains($foto, '/')) {
                $allPhotos[] = asset('storage/' . $foto);
            }
        }
    }
    $photoJson = json_encode($allPhotos);
@endphp

<a href="{{ route('kos.show', $kos) }}"
   class="group flex flex-col bg-white rounded-2xl border border-slate-100 overflow-hidden transition-all duration-500 hover:shadow-[0_20px_50px_-15px_rgba(0,0,0,0.12)] hover:-translate-y-2 hover:border-slate-200/80">

    {{-- ── Image Carousel ── --}}
    <div class="{{ $compact ? 'h-44' : 'h-56 sm:h-60' }} w-full relative overflow-hidden bg-slate-100"
         x-data="{ photos: {{ $photoJson }}, current: 0 }"
         @mouseenter.stop @mouseleave.stop>

        {{-- Slides --}}
        <template x-for="(photo, i) in photos" :key="i">
            <div class="absolute inset-0 transition-opacity duration-500"
                 :class="i === current ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                {{-- Skeleton placeholder --}}
                <div class="absolute inset-0 bg-slate-200 animate-pulse"></div>
                <img :src="photo"
                     :alt="'{{ $kos->nama }} - foto ' + (i + 1)"
                     class="w-full h-full object-cover transition-transform duration-700 ease-out relative z-10"
                     :class="i === current ? 'scale-100' : 'scale-105'"
                     loading="lazy"
                     @load="$el.previousElementSibling.style.display='none'"
                     onerror="this.src='https://placehold.co/600x400/f0fdfa/0d9488?text=BaKos'">
            </div>
        </template>

        {{-- Hover gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-20 pointer-events-none"></div>

        {{-- Prev/Next Buttons (show only when multiple photos) --}}
        <template x-if="photos.length > 1">
            <div>
                <button @click.prevent="current = (current - 1 + photos.length) % photos.length"
                        class="absolute left-2 top-1/2 -translate-y-1/2 z-30 w-7 h-7 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-white hover:scale-110 active:scale-95">
                    <svg class="w-3.5 h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click.prevent="current = (current + 1) % photos.length"
                        class="absolute right-2 top-1/2 -translate-y-1/2 z-30 w-7 h-7 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-white hover:scale-110 active:scale-95">
                    <svg class="w-3.5 h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Dot Indicators --}}
                <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 z-30 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <template x-for="(p, i) in photos" :key="i">
                        <button @click.prevent="current = i"
                                class="rounded-full transition-all duration-200"
                                :class="i === current ? 'w-4 h-1.5 bg-white' : 'w-1.5 h-1.5 bg-white/60 hover:bg-white/90'">
                        </button>
                    </template>
                </div>
            </div>
        </template>

        {{-- Top badges row --}}
        <div class="absolute top-3 left-3 right-3 flex items-start justify-between z-20">
            {{-- Jenis badge --}}
            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1.5 rounded-lg shadow-lg backdrop-blur-md tracking-wide uppercase
                {{ $kos->jenis === 'putra'
                    ? 'bg-sky-500/85 text-white ring-1 ring-sky-400/30'
                    : ($kos->jenis === 'putri'
                        ? 'bg-pink-500/85 text-white ring-1 ring-pink-400/30'
                        : 'bg-slate-800/85 text-white ring-1 ring-slate-600/30') }}">
                @if($kos->jenis === 'putra')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @elseif($kos->jenis === 'putri')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @else
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                @endif
                {{ $kos->jenis }}
            </span>

            {{-- Favorite button --}}
            <button type="button"
                    onclick="event.preventDefault(); event.stopPropagation();"
                    class="w-8 h-8 rounded-lg bg-white/80 backdrop-blur-md flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white hover:scale-110 active:scale-95 ring-1 ring-black/5"
                    title="Simpan ke favorit">
                <svg class="w-4 h-4 text-slate-500 hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>

        {{-- Bottom left: Rating --}}
        @if($kos->rating > 0)
            <div class="absolute bottom-3 left-3 z-20 inline-flex items-center gap-1.5 bg-white/90 backdrop-blur-md px-2.5 py-1.5 rounded-lg shadow-lg ring-1 ring-black/5">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-xs font-bold text-slate-800">{{ number_format($kos->rating, 1) }}</span>
            </div>
        @endif

        {{-- Bottom right: Availability (Urgent badge style) --}}
        <div class="absolute bottom-3 right-3 z-20">
            @if($kos->is_available)
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2.5 py-1.5 rounded-lg bg-emerald-500 text-white shadow-lg shadow-emerald-500/30 ring-1 ring-emerald-400/30">
                    <span class="relative flex w-2 h-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    Tersedia
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2.5 py-1.5 rounded-lg bg-rose-500/90 text-white shadow-lg ring-1 ring-rose-400/30">
                    <span class="w-2 h-2 rounded-full bg-white/60"></span>
                    Penuh
                </span>
            @endif
        </div>
    </div>

    {{-- ── Content ── --}}
    <div class="flex flex-col flex-1 p-4 pt-3.5">

        {{-- Title --}}
        <h3 class="font-bold text-slate-900 text-[15px] leading-snug line-clamp-1 group-hover:text-brand-600 transition-colors duration-300 mb-1.5">
            {{ $kos->nama }}
        </h3>

        {{-- Location --}}
        <p class="text-slate-400 text-sm flex items-center gap-1.5 mb-3 truncate">
            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="truncate">{{ $kos->kecamatan }}, {{ $kos->kota ?? 'Manado' }}</span>
        </p>

        {{-- Facilities preview --}}
        @php
            $fasilitas = is_array($kos->fasilitas) ? $kos->fasilitas : [];
            if (empty($fasilitas) && !empty($kos->fasilitas_kamar) && is_array($kos->fasilitas_kamar)) {
                $fasilitas = $kos->fasilitas_kamar;
            }
            $facilityIcons = [
                'WiFi' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.858 15.355-5.858 21.213 0"/>',
                'AC' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                'Kamar Mandi Dalam' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                'Parkir' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
            ];
            $shownFacilities = array_slice($fasilitas, 0, 3);
            $remaining = count($fasilitas) - 3;
        @endphp

        @if(count($fasilitas) > 0)
            <div class="flex items-center gap-1.5 mb-4 flex-wrap">
                @foreach($shownFacilities as $f)
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-500 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                        @if(isset($facilityIcons[$f]))
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $facilityIcons[$f] !!}</svg>
                        @endif
                        {{ Str::limit($f, 10) }}
                    </span>
                @endforeach
                @if($remaining > 0)
                    <span class="text-[10px] font-bold text-brand-600 bg-brand-50 px-2 py-1 rounded-md border border-brand-100">
                        +{{ $remaining }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Price footer --}}
        <div class="mt-auto pt-3 border-t border-slate-50 flex items-end justify-between">
            <div>
                <span class="text-lg font-extrabold text-slate-900 tracking-tight group-hover:text-brand-600 transition-colors">{{ $kos->harga_format }}</span>
                <span class="text-slate-400 text-xs font-medium">/bulan</span>
            </div>
            {{-- CTA hint --}}
            <div class="flex items-center gap-1 text-xs font-semibold text-brand-500 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                Lihat Detail
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>
    </div>
</a>