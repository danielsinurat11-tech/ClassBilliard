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
        if (! Schema::hasTable('tentang_kamis')) {
            Schema::create('tentang_kamis', function (Blueprint $table) {
                $table->id();
                $table->string('title')->default('Tentang Kami');
                $table->text('subtitle')->nullable();
                $table->text('visi')->nullable();
                $table->text('misi')->nullable();
                $table->text('arah_gerak')->nullable();
                $table->string('video_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentang_kamis');
    }
};
