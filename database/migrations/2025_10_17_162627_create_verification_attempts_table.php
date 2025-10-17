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
        Schema::create('verification_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('email'); // Store email in case user changes it later
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->text('error_message')->nullable(); // Store error details if failed
            $table->timestamp('sent_at')->nullable(); // When the email was sent
            $table->timestamp('verified_at')->nullable(); // When the email was verified
            $table->integer('attempt_count')->default(1); // Number of attempts
            $table->json('metadata')->nullable(); // Additional data about the attempt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_attempts');
    }
};
