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
            $table->string('recurrence_pattern')->nullable()->after('is_recurring');
            $table->date('recurrence_end_date')->nullable()->after('recurrence_pattern');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitations', function (Blueprint $table) {
            $table->dropColumn(['recurrence_pattern', 'recurrence_end_date']);
        });
    }
};
