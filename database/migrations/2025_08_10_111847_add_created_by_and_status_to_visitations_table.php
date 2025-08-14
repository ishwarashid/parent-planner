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
        Schema::table('visitations', function (Blueprint $table) {
            // Add the user who is ASSIGNED the visitation (renaming for clarity if needed)
            // Your existing 'parent_id' is perfect for this.

            // Add the user who CREATED the visitation record
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('id');

            // Add the status of the visitation
            $table->string('status')->default('Scheduled')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitations', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['created_by', 'status']);
        });
    }
};
