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
        Schema::table('children', function (Blueprint $table) {
            $table->string('gender')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('national_id')->nullable();
            $table->text('health_conditions')->nullable();
            $table->string('primary_residence')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_grade')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->text('extracurricular_activities')->nullable();
            $table->text('doctor_info')->nullable();
            $table->text('emergency_contact_info')->nullable();
            $table->text('special_needs')->nullable();
            $table->text('other_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'blood_type',
                'national_id',
                'health_conditions',
                'primary_residence',
                'school_name',
                'school_grade',
                'profile_photo_path',
                'extracurricular_activities',
                'doctor_info',
                'emergency_contact_info',
                'special_needs',
                'other_info',
            ]);
        });
    }
};