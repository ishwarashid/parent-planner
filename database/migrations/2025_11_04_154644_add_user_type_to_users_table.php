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
        // Add a nullable column to track if the user has a professional profile
        // This helps to know if a professional profile exists for the user
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_professional_profile')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('has_professional_profile');
        });
    }
};
