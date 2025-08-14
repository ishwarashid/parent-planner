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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('dob');
            $table->text('allergies')->nullable();
            $table->string('school_info')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('primary_residence')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_grade')->nullable();
            $table->string('color')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->text('extracurricular_activities')->nullable();
            $table->text('doctor_info')->nullable();
            $table->text('emergency_contact_info')->nullable();
            $table->text('special_needs')->nullable();
            $table->text('other_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
