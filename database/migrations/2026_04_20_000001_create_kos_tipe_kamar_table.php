<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kos_tipe_kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kos_id')->constrained('kos')->cascadeOnDelete();
            $table->string('nama_tipe');                         // e.g. "Kamar Standar", "Kamar AC"
            $table->unsignedBigInteger('harga_per_bulan');
            $table->unsignedBigInteger('harga_per_tahun')->nullable();
            $table->unsignedInteger('luas_kamar')->nullable();   // m²
            $table->json('fasilitas')->nullable();               // ["AC","Kamar Mandi Dalam",...]
            $table->unsignedInteger('kapasitas')->default(1);    // jumlah kamar tersedia
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('urutan')->default(0);       // untuk pengurutan tampilan
            $table->timestamps();

            $table->index('kos_id');
            $table->index('harga_per_bulan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos_tipe_kamar');
    }
};
