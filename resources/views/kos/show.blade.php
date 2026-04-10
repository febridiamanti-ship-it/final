@extends('layouts.app')

@section('title', $kos->nama . ' - BaKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header Judul --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-bold px-3 py-1 rounded-md 
                {{ $kos->jenis === 'putra' ? 'bg-sky-100 text-sky-700' : ($kos->jenis === 'putri' ? 'bg-pink-100 text-pink-700' : 'bg-slate-100 text-slate-700') }}">
                Kos {{ strtoupper($kos->jenis) }}
            </span>
            @if($kos->rating > 0)
            <div class="flex items-center gap-1 text-sm font-bold text-slate-800">
                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ number_format($kos->rating, 1) }} <span class="text-slate-500 font-normal">({{ $kos->total_review }} ulasan)</span>
            </div>
            @endif
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $kos->nama }}</h1>
        <p class="text-slate-500 mt-2 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $kos->alamat }}, {{ $kos->kecamatan }}, {{ $kos->kota }}
        </p>
    </div>

    {{-- Galeri Foto Grid ala Airbnb --}}
    <div class="grid grid-cols-4 grid-rows-2 gap-2 h-[400px] sm:h-[500px] rounded-2xl overflow-hidden mb-12">
        
        {{-- Foto Utama (Besar di sebelah kiri) --}}
        <div class="col-span-4 sm:col-span-2 row-span-2 relative group cursor-pointer bg-slate-100">
            <img src="{{ $kos->foto_utama_url }}" alt="Foto Utama" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" onerror="this.src='https://placehold.co/800x600/e2e8f0/94a3b8'">
        </div>
        
        {{-- Foto Tambahan (Disembunyikan di Mobile) --}}
        @if(!empty($kos->foto_tambahan) && is_array($kos->foto_tambahan))
            @php
                // FILTER PINTAR: Buang teks yang nyasar, hanya ambil yang mengandung garis miring '/'
                $validFotos = array_filter($kos->foto_tambahan, function($item) {
                    return is_string($item) && str_contains($item, '/');
                });
            @endphp
            
            {{-- Looping maksimal 4 foto tambahan yang VALID --}}
            @foreach(array_slice($validFotos, 0, 4) as $foto)
            <div class="hidden sm:block col-span-1 row-span-1 relative group overflow-hidden bg-slate-100">
                <img src="{{ asset('storage/' . $foto) }}" alt="Foto Tambahan" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" onerror="this.src='https://placehold.co/400x300/e2e8f0/94a3b8?text=No+Image'">
            </div>
            @endforeach

            {{-- Jika foto kurang dari 4, isi sisanya dengan placeholder agar grid tidak rusak --}}
            @for($i = count($validFotos); $i < 4; $i++)
            <div class="hidden sm:block col-span-1 row-span-1 relative group overflow-hidden bg-slate-100">
                <img src="https://placehold.co/400x300/e2e8f0/94a3b8?text=Kos" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            </div>
            @endfor

        @else
            {{-- Default Placeholders jika tidak ada foto_tambahan sama sekali di DB --}}
            <div class="hidden sm:block col-span-1 row-span-1 relative group overflow-hidden"><img src="https://placehold.co/400x300/e2e8f0/94a3b8?text=Kamar" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"></div>
            <div class="hidden sm:block col-span-1 row-span-1 relative group overflow-hidden"><img src="https://placehold.co/400x300/e2e8f0/94a3b8?text=Kamar+Mandi" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"></div>
            <div class="hidden sm:block col-span-1 row-span-1 relative group overflow-hidden"><img src="https://placehold.co/400x300/e2e8f0/94a3b8?text=Dapur" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"></div>
            <div class="hidden sm:block col-span-1 row-span-1 relative group overflow-hidden"><img src="https://placehold.co/400x300/e2e8f0/94a3b8?text=Fasilitas" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"></div>
        @endif

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
        
        {{-- Konten Kiri (Detail Kos) --}}
        <div class="lg:col-span-2 space-y-10">
            
            {{-- Info Pemilik --}}
            <div class="flex items-center justify-between pb-8 border-b border-slate-200">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-1">
                        Dikelola oleh {{ $kos->pemilik?->name ?? $kos->nama_pemilik ?? 'Pemilik' }}
                    </h2>
                    <p class="text-slate-500 text-sm">
                        Bergabung sejak {{ $kos->pemilik?->created_at?->format('Y') ?? '2024' }}
                    </p>
                </div>
                <img src="{{ $kos->pemilik?->foto_profil_url ?? 'https://ui-avatars.com/api/?name='.urlencode($kos->pemilik?->name ?? $kos->nama_pemilik ?? 'P') }}" 
                     alt="Pemilik" class="w-14 h-14 rounded-full object-cover border border-slate-200">
            </div>

            {{-- Deskripsi --}}
            <div>
                <h3 class="text-xl font-bold text-slate-900 mb-4">Tentang Kos Ini</h3>
                <div class="text-slate-600 leading-relaxed space-y-4">
                    {!! nl2br(e($kos->deskripsi)) !!}
                </div>
            </div>

            {{-- Fasilitas Kamar --}}
            @if(!empty($kos->fasilitas_kamar) && is_array($kos->fasilitas_kamar))
            <div>
                <h3 class="text-xl font-bold text-slate-900 mb-4">Fasilitas Kamar</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4">
                    @foreach($kos->fasilitas_kamar as $fasilitas)
                    <div class="flex items-center gap-3 text-slate-600">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $fasilitas }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Fasilitas Bersama --}}
            @if(!empty($kos->fasilitas_bersama) && is_array($kos->fasilitas_bersama))
            <div>
                <h3 class="text-xl font-bold text-slate-900 mb-4">Fasilitas Bersama</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4">
                    @foreach($kos->fasilitas_bersama as $fasilitas)
                    <div class="flex items-center gap-3 text-slate-600">
                        <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $fasilitas }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Aturan Kos --}}
            <div class="bg-brand-50 rounded-2xl p-6 border border-brand-100">
                <h3 class="text-lg font-bold text-brand-900 mb-3">Aturan Kos</h3>
                <p class="text-brand-700 text-sm leading-relaxed">{{ $kos->peraturan ?: 'Silakan tanyakan langsung ke pemilik terkait aturan kos yang berlaku.' }}</p>
            </div>

            {{-- SEKSI LOKASI PETA --}}
            <div class="mt-12 pt-10 border-t border-slate-200">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Lokasi Kos
                </h3>

                @if($kos->latitude && $kos->longitude)
                    {{-- Tampilkan Peta Leaflet secara default --}}
                    <div class="relative w-full h-[400px] rounded-2xl overflow-hidden border border-slate-200 shadow-sm z-0">
                        <div id="map" class="absolute inset-0"></div>
                    </div>
                    <p class="text-sm text-slate-500 mt-3 font-medium">
                        <span class="font-bold text-slate-700">Alamat:</span> {{ $kos->alamat }}, {{ $kos->kelurahan }}, {{ $kos->kecamatan }}, Kota {{ $kos->kota }}
                    </p>
                @else
                    <div class="w-full p-6 rounded-2xl bg-amber-50 border border-amber-100 flex items-center gap-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-amber-800 font-bold text-sm">Titik Koordinat Belum Tersedia</h4>
                            <p class="text-amber-700 text-sm mt-0.5">Pemilik kos ini belum mengatur titik lokasi di peta. Silakan hubungi pemilik untuk menanyakan patokan lokasi.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            {{-- SEKSI ULASAN & RATING --}}
            <div class="mt-12 pt-10 border-t border-slate-200">
                <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Ulasan Penyewa 
                    <span class="text-slate-500 font-medium text-lg">({{ $kos->total_review }})</span>
                </h3>

                {{-- Form Tulis Ulasan --}}
                @auth
                    @if(!$kos->reviews->where('user_id', auth()->id())->count())
                    <form action="{{ route('reviews.store', $kos) }}" method="POST" class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-8">
                        @csrf
                        <h4 class="font-bold text-slate-900 mb-3">Tulis Ulasan Anda</h4>
                        
                        <div class="mb-4">
                            <label class="block text-sm text-slate-600 mb-2">Pilih Rating:</label>
                            <div class="flex gap-4">
                                @for($i=5; $i>=1; $i--)
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="text-amber-500 focus:ring-amber-500" {{ $i==5 ? 'checked' : '' }}>
                                    <span class="text-sm font-bold">{{ $i }} Bintang</span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <textarea name="komentar" rows="3" placeholder="Bagaimana pengalaman Anda tinggal di sini? (Opsional)" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
                        </div>
                        
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition-all">Kirim Ulasan</button>
                    </form>
                    @else
                    <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl text-sm mb-8 border border-emerald-100">
                        Anda sudah memberikan ulasan untuk kos ini. Terima kasih!
                    </div>
                    @endif
                @else
                    <div class="bg-slate-50 text-slate-600 px-4 py-4 rounded-xl text-sm mb-8 border border-slate-100 flex items-center justify-between">
                        <span>Silakan login terlebih dahulu untuk memberikan ulasan.</span>
                        <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:underline">Login di sini</a>
                    </div>
                @endauth

                {{-- Daftar Ulasan Dinamis --}}
                <div class="space-y-6">
                    @forelse($kos->reviews as $review)
                    <div class="border-b border-slate-100 pb-6 last:border-0">
                        <div class="flex items-center gap-3 mb-2">
                            <img src="{{ $review->user->foto_profil_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $review->user->name }}</h4>
                                <div class="flex items-center gap-2">
                                    <div class="flex text-amber-400">
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @if($review->komentar)
                        <p class="text-slate-600 text-sm mt-2 leading-relaxed">{{ $review->komentar }}</p>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-slate-500 text-sm">Belum ada ulasan untuk kos ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Konten Kanan (Card Melayang) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-28 bg-white border border-slate-200 rounded-2xl p-6 shadow-float">
                <div class="mb-6">
                    <span class="text-2xl font-extrabold text-slate-900">{{ $kos->harga_format }}</span>
                    <span class="text-slate-500 font-medium"> / bulan</span>
                </div>

                {{-- Informasi Singkat --}}
                <div class="space-y-3 mb-6 p-4 border border-slate-100 rounded-xl bg-slate-50">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tipe Kamar</span>
                        <span class="font-semibold text-slate-900">{{ ucfirst($kos->tipe_kamar) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Ketersediaan</span>
                        @if($kos->is_available)
                            <span class="font-semibold text-emerald-600">Kamar Tersedia</span>
                        @else
                            <span class="font-semibold text-red-600">Penuh</span>
                        @endif
                    </div>
                </div>

                {{-- Tombol Hubungi via WhatsApp --}}
                <a href="https://wa.me/{{ $kos->pemilik?->telepon ?? $kos->telepon_pemilik }}?text=Halo,%20saya%20tertarik%20dengan%20kos%20{{ urlencode($kos->nama) }}" 
                   target="_blank"
                   class="w-full flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-md shadow-brand-500/30">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.183-.573c.978.582 1.994.922 3.141.922 3.18 0 5.766-2.586 5.768-5.766 0-3.181-2.586-5.769-5.766-5.769zm3.22 8.271c-.177.493-.902.928-1.292.971-.352.039-.814.155-2.612-.559-2.16-1.144-3.535-3.353-3.642-3.497-.107-.144-.868-1.155-.868-2.203 0-1.047.545-1.565.735-1.772.189-.206.411-.259.549-.259.138 0 .275.004.389.009.123.007.288-.046.442.327.164.402.562 1.375.612 1.474.05.099.083.215.016.349-.066.134-.101.217-.201.334-.099.117-.21.258-.298.339-.098.09-.204.189-.089.387.115.198.513.845 1.096 1.369.754.675 1.391.882 1.59.982.199.1.316.084.432-.047.115-.133.498-.58.632-.78.133-.198.267-.165.449-.097.182.069 1.151.543 1.348.641.198.098.33.147.379.229.049.082.049.475-.128.968z"/></svg>
                    Chat Pemilik via WhatsApp
                </a>

                <button class="w-full mt-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Simpan ke Favorit
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Script Peta hanya dimuat jika koordinat ada --}}
@if($kos->latitude && $kos->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Ambil koordinat dari database
        var lat = {{ $kos->latitude }};
        var lng = {{ $kos->longitude }};
        var namaKos = "{{ $kos->nama }}";
        var hargaKos = "{{ $kos->harga_format }}";

        // 2. Inisialisasi peta ke dalam id="map", set zoom level ke 16
        var map = L.map('map', {
            scrollWheelZoom: false // Mencegah peta ter-zoom tidak sengaja saat user scroll halaman
        }).setView([lat, lng], 16);

        // 3. Muat gambar peta dari OpenStreetMap (Gratis)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // 4. Tambahkan Pin (Marker) merah kustom
        var customIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);

        // 5. Tambahkan Popup saat pin diklik
        marker.bindPopup(
            "<div class='text-center p-1'>" +
            "<strong class='text-slate-900 block mb-1'>" + namaKos + "</strong>" +
            "<span class='text-brand-600 font-bold'>" + hargaKos + "/bln</span>" +
            "</div>"
        ).openPopup();
    });
</script>
@endif
@endpush