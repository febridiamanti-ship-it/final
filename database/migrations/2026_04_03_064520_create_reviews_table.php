<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke kos (jika kos dihapus, review otomatis terhapus)
            $table->foreignId('kos_id')->constrained('kos')->cascadeOnDelete();
            // Hubungkan ke user pemberi review
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Rating 1 sampai 5
            $table->unsignedTinyInteger('rating'); 
            // Komentar bersifat opsional (boleh null)
            $table->text('komentar')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};