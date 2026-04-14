<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting: User dulu, baru Kos
        $this->call([
            UserSeeder::class,
            KosSeeder::class,
        ]);
    }
}
