<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───
        User::create([
            'name'     => 'Admin Sandy Nagaring',
            'email'    => 'admin@bakos.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'telepon'  => '081100000000',
            'kota'     => 'Manado',
            'bio'      => 'Administrator BaKos – Platform Cari Kos Tanpa Ribet.',
        ]);

        // ─── Pemilik Kos ───
        User::create([
            'name'     => 'Pak Refzi Kasehung',
            'email'    => 'pemilik@bakos.id',
            'password' => Hash::make('pemilik123'),
            'role'     => 'pemilik',
            'telepon'  => '082312345678',
            'kota'     => 'Manado',
            'bio'      => 'Pemilik beberapa kos di kawasan Bunaken Manado.',
        ]);

        // ─── Pencari Kos ───
        User::create([
            'name'     => 'Inkria lohonauman',
            'email'    => 'pencari@bakos.id',
            'password' => Hash::make('pencari123'),
            'role'     => 'pencari',
            'telepon'  => '083398765432',
            'kota'     => 'Manado',
            'bio'      => 'Mahasiswa Amik Manado, sedang cari kos di Pandu.',
        ]);
    }
}
