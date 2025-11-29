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
        Schema::create('landing_page_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('duration')->nullable();
            $table->date('date')->nullable();
            $table->string('video_url')->nullable(); // URL to the actual video file
            $table->string('thumbnail_url')->nullable(); // URL to the thumbnail image
            $table->boolean('is_active')->default(true); // To enable/disable the video
            $table->string('video_type')->default('landing'); // To identify the type of video
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_videos');
    }
};
