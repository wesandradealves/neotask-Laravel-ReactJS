<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('youtube_link');
            $table->unsignedBigInteger('plays')->default(0); // campo necessário
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
