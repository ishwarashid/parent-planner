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
        Schema::create('help_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('duration');
            $table->date('date');
            $table->string('video_url')->nullable(); // URL to the actual video file
            $table->string('thumbnail_url')->nullable(); // URL to the thumbnail image
            $table->integer('order')->default(0); // To allow custom ordering
            $table->boolean('is_active')->default(true); // To enable/disable videos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_videos');
    }
};
