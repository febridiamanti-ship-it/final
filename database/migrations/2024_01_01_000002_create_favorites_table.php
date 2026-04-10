<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'kos_id']); // 1 user 1 kos = 1 favorit
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
