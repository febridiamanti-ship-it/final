<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['putra','putri','campur'])->default('campur');
            $table->enum('tipe_kamar', ['kos','kontrakan','apartemen'])->default('kos');
            $table->unsignedBigInteger('harga_per_bulan')->default(0);
            $table->unsignedBigInteger('harga_per_tahun')->nullable();
            $table->unsignedInteger('luas_kamar')->nullable();
            $table->text('alamat');
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto_utama')->nullable();
            $table->json('foto_tambahan')->nullable();
            $table->json('fasilitas_kamar')->nullable();
            $table->json('fasilitas_bersama')->nullable();
            $table->text('peraturan')->nullable();
            $table->string('nama_pemilik', 100);
            $table->string('telepon_pemilik', 20);
            $table->boolean('is_available')->default(true);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_review')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['kota','jenis','is_available']);
            $table->index('harga_per_bulan');
        });
    }
    public function down(): void { Schema::dropIfExists('kos'); }
};
