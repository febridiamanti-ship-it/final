@extends('layouts.app')
@section('title', 'Pasang Kos – BaKos')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="createKosForm()">

    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pasang Iklan Kos Baru</h1>
        <p class="text-slate-500 mt-2 text-sm">Lengkapi data properti Anda untuk menarik lebih banyak penyewa.</p>
    </div>

    {{-- KOTAK PERINGATAN ERROR VALIDASI (Dipindah ke atas agar langsung terlihat) --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl shadow-sm">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <h3 class="text-sm font-bold text-red-800">Oops! Ada yang perlu diperbaiki:</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 ml-7 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stepper Modern Minimalis --}}
    <div class="mb-12">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-1 bg-slate-100 rounded-full z-0">
                <div class="h-full bg-brand-500 rounded-full transition-all duration-500" :style="'width:'+((currentStep-1)/3*100)+'%'"></div>
            </div>
            @foreach([1=>'Info Dasar',2=>'Lokasi',3=>'Fasilitas',4=>'Media'] as $num=>$label)
            <div class="relative z-10 flex flex-col items-center gap-2 bg-white px-2">
                <div :class="{{ $num }} <= currentStep ? 'bg-brand-500 text-white ring-4 ring-brand-50' : 'bg-slate-200 text-slate-500'"
                     class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300">
                    <template x-if="{{ $num }} < currentStep">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="{{ $num }} >= currentStep"><span>{{ $num }}</span></template>
                </div>
                <span :class="{{ $num }} <= currentStep ? 'text-slate-900 font-bold' : 'text-slate-400 font-medium'" class="hidden sm:block text-xs">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <form method="POST" action="{{ route('kos.store') }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-100 shadow-float overflow-hidden">
        @csrf

        <div class="p-8 sm:p-10">
            {{-- STEP 1: Info Dasar --}}
            <div x-show="currentStep===1" x-transition.opacity.duration.300ms class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kos <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Misal: Kos Cendrawasih Indah"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Singkat <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" required placeholder="Jelaskan kelebihan kos ini..."
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all resize-none">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                        <select name="jenis" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                            <option value="">Pilih...</option>
                            <option value="putra">Putra</option>
                            <option value="putri">Putri</option>
                            <option value="campur">Campur</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                        <select name="tipe_kamar" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                            <option value="">Pilih...</option>
                            <option value="kos">Kos</option>
                            <option value="kontrakan">Kontrakan</option>
                            <option value="apartemen">Apartemen</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Luas (m²)</label>
                        <input type="number" name="luas_kamar" placeholder="Misal: 12"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-slate-100 pt-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga / Bulan (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-sm">Rp</span>
                            <input type="number" name="harga_per_bulan" required placeholder="1000000"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga / Tahun (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-sm">Rp</span>
                            <input type="number" name="harga_per_tahun" placeholder="10000000"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: Lokasi --}}
            <div x-show="currentStep===2" x-transition.opacity.duration.300ms class="space-y-6" style="display: none;">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="alamat" required placeholder="Sertakan nomor jalan/rumah"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                </div>
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kota</label>
                        {{-- Kota sengaja dibikin readonly agar seragam --}}
                        <input type="text" name="kota" value="Manado" required readonly
                               class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-600 focus:outline-none cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan</label>
                        <input type="text" name="kecamatan" placeholder="Kecamatan"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                    </div>
                </div>
                
                {{-- INI CARA 2: Input Tersembunyi untuk Provinsi --}}
                <input type="hidden" name="provinsi" value="Sulawesi Utara">

                <div class="border-t border-slate-100 pt-6 mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pin Lokasi Peta</label>
                    <div id="pickMap" class="h-80 rounded-2xl overflow-hidden border border-slate-200 shadow-inner z-0" x-init="initPickMap()"></div>
                    <input type="hidden" name="latitude" id="lat_input">
                    <input type="hidden" name="longitude" id="lng_input">
                    <p class="text-xs text-slate-500 mt-2 font-medium" id="coordDisplay">Klik area peta untuk menandai titik pasti lokasi kos.</p>
                </div>
            </div>

            {{-- STEP 3: Fasilitas --}}
            <div x-show="currentStep===3" x-transition.opacity.duration.300ms class="space-y-8" style="display: none;">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Fasilitas Dalam Kamar</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach(['AC','Kasur','Lemari','Meja Belajar','WiFi','Kamar Mandi Dalam','TV','Kulkas'] as $fas)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-brand-400 hover:bg-brand-50/50 transition-all has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50">
                            <input type="checkbox" name="fasilitas_kamar[]" value="{{ $fas }}" class="text-brand-600 focus:ring-brand-500 w-4 h-4 rounded border-slate-300">
                            <span class="text-sm font-medium text-slate-700">{{ $fas }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Fasilitas Bersama</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach(['Dapur','Parkir Motor','Parkir Mobil','Laundry','CCTV','Security 24 jam','Rooftop'] as $fas)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-brand-400 hover:bg-brand-50/50 transition-all has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50">
                            <input type="checkbox" name="fasilitas_bersama[]" value="{{ $fas }}" class="text-brand-600 focus:ring-brand-500 w-4 h-4 rounded border-slate-300">
                            <span class="text-sm font-medium text-slate-700">{{ $fas }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- STEP 4: Media & Kontak --}}
            <div x-show="currentStep===4" x-transition.opacity.duration.300ms class="space-y-6" style="display: none;">
                
                {{-- Area Upload --}}
                <div class="border-2 border-dashed border-slate-300 hover:border-brand-500 bg-slate-50 rounded-2xl p-8 text-center transition-colors cursor-pointer"
                     @dragover.prevent @drop.prevent="handleDrop($event)"
                     onclick="document.getElementById('foto_utama_input').click()">
                    <template x-if="!previewMain">
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 bg-white rounded-full shadow-sm flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">Upload Foto Utama Kos</h4>
                            <p class="text-xs text-slate-500 mt-1">Seret & lepas gambar di sini, atau klik untuk mencari.</p>
                        </div>
                    </template>
                    <template x-if="previewMain">
                        <div class="relative w-full">
                            <img :src="previewMain" class="w-full h-56 object-cover rounded-xl shadow-sm">
                            <button type="button" @click.stop="previewMain=null;document.getElementById('foto_utama_input').value=''"
                                    class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur text-red-500 hover:bg-red-500 hover:text-white rounded-full flex items-center justify-center transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
                <input type="file" id="foto_utama_input" name="foto_utama" accept="image/*" class="hidden" @change="handleMainPhoto($event)">

                {{-- Foto Tambahan (Galeri) --}}
                <div class="border-t border-slate-100 pt-6 mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Foto Tambahan (Galeri) <span class="text-xs font-normal text-slate-500 ml-1">Boleh dikosongkan</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Foto Dalam Kamar</label>
                            <input type="file" name="foto_tambahan[kamar]" accept="image/*"
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Foto Kamar Mandi</label>
                            <input type="file" name="foto_tambahan[kamar_mandi]" accept="image/*"
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Foto Fasilitas Bersama</label>
                            <input type="file" name="foto_tambahan[fasilitas]" accept="image/*"
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Foto Area Dapur</label>
                            <input type="file" name="foto_tambahan[dapur]" accept="image/*"
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-colors">
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-slate-100 pt-6 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pengelola <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required placeholder="Nama Anda"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="telepon_pemilik" value="{{ old('telepon_pemilik') }}" required placeholder="08xxxxxxxxxx"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500">
                    </div>
                </div>
            </div>

        </div>

        {{-- Navigasi Bawah --}}
        <div class="bg-slate-50 px-8 py-5 border-t border-slate-100 flex items-center justify-between">
            <div>
                <button type="button" @click="prevStep" x-show="currentStep>1"
                        class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                    Kembali
                </button>
            </div>
            
            <template x-if="currentStep<4">
                <button type="button" @click="nextStep"
                        class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-3 rounded-xl text-sm transition-all shadow-md">
                    Lanjut
                </button>
            </template>
            <template x-if="currentStep===4">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-8 py-3 rounded-xl text-sm transition-all shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Publikasikan Iklan
                </button>
            </template>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function createKosForm() {
    return {
        currentStep: 1,
        previewMain: null,
        pickMap: null,
        marker: null,

        nextStep() { if(this.currentStep<4) this.currentStep++; if(this.currentStep===2) this.$nextTick(()=>this.initPickMap()); },
        prevStep() { if(this.currentStep>1) this.currentStep--; },

        handleMainPhoto(e) {
            const f=e.target.files[0];
            if(f){const r=new FileReader();r.onload=(ev)=>this.previewMain=ev.target.result;r.readAsDataURL(f);}
        },
        handleDrop(e) {
            const f=e.dataTransfer.files[0];
            if(f&&f.type.startsWith('image/')){
                const dt=new DataTransfer();dt.items.add(f);document.getElementById('foto_utama_input').files=dt.files;
                const r=new FileReader();r.onload=(ev)=>this.previewMain=ev.target.result;r.readAsDataURL(f);
            }
        },

        initPickMap() {
            if(this.pickMap) return;
            // Default ke Manado
            this.pickMap = L.map('pickMap').setView([1.4956, 124.8443], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',{attribution:'&copy; OpenStreetMap'}).addTo(this.pickMap);
            this.pickMap.on('click',(e)=>{
                const {lat,lng}=e.latlng;
                document.getElementById('lat_input').value=lat.toFixed(7);
                document.getElementById('lng_input').value=lng.toFixed(7);
                document.getElementById('coordDisplay').textContent=`📍 Titik tersimpan: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                if(this.marker) this.marker.remove();
                this.marker=L.marker([lat,lng]).addTo(this.pickMap);
            });
            setTimeout(() => { this.pickMap.invalidateSize(); }, 300);
        }
    }
}
</script>
@endpush