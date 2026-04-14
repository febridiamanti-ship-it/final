@extends('layouts.app')
@section('title', 'Daftar – BaKos')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">

        {{-- Logo Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 shadow-md">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-slate-900 mt-6 tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Pilih tipe akun dan lengkapi data dirimu.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-float border border-slate-100 p-8 sm:p-10">

            {{-- Role selector Modern --}}
            <div x-data="{ role: '{{ old('role', 'pencari') }}' }" class="mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <label @click="role='pencari'"
                           :class="role==='pencari' ? 'border-brand-500 bg-brand-50/50 ring-1 ring-brand-500' : 'border-slate-200 hover:border-slate-300 bg-white'"
                           class="cursor-pointer border-2 rounded-2xl p-4 transition-all relative overflow-hidden">
                        <div x-show="role==='pencari'" class="absolute top-3 right-3 text-brand-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="text-2xl mb-2">🔍</div>
                        <div class="font-bold text-sm text-slate-900">Pencari Kos</div>
                        <div class="text-xs text-slate-500 mt-1">Saya ingin mencari kamar</div>
                        <input type="radio" name="role" value="pencari" x-model="role" class="hidden">
                    </label>
                    
                    <label @click="role='pemilik'"
                           :class="role==='pemilik' ? 'border-brand-500 bg-brand-50/50 ring-1 ring-brand-500' : 'border-slate-200 hover:border-slate-300 bg-white'"
                           class="cursor-pointer border-2 rounded-2xl p-4 transition-all relative overflow-hidden">
                        <div x-show="role==='pemilik'" class="absolute top-3 right-3 text-brand-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="text-2xl mb-2">🏠</div>
                        <div class="font-bold text-sm text-slate-900">Pemilik Kos</div>
                        <div class="text-xs text-slate-500 mt-1">Saya ingin pasang iklan</div>
                        <input type="radio" name="role" value="pemilik" x-model="role" class="hidden">
                    </label>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5 mt-6 border-t border-slate-100 pt-6">
                    @csrf
                    <input type="hidden" name="role" :value="role">

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-100 text-red-600 rounded-xl px-4 py-3 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. WhatsApp</label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08..."
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 pr-11 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                                <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ulangi Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:bg-white focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl text-sm transition-all shadow-md mt-2">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center text-sm text-slate-500 mt-8">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:text-brand-700 hover:underline transition-colors">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection