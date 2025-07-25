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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('terrain_id')->constrained('terrains')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate favorites
            $table->unique(['user_id', 'terrain_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
