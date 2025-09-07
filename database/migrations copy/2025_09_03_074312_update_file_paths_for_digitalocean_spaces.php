<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update children table profile_photo_path to remove 'profile_photos/' prefix if needed
        DB::table('children')
            ->where('profile_photo_path', 'LIKE', 'profile_photos/%')
            ->update([
                'profile_photo_path' => DB::raw("REPLACE(profile_photo_path, 'profile_photos/', '')")
            ]);

        // Update documents table file_url to remove 'documents/' prefix if needed
        DB::table('documents')
            ->where('file_url', 'LIKE', 'documents/%')
            ->update([
                'file_url' => DB::raw("REPLACE(file_url, 'documents/', '')")
            ]);

        // Update expenses table receipt_url to remove 'receipts/' prefix if needed
        DB::table('expenses')
            ->where('receipt_url', 'LIKE', 'receipts/%')
            ->update([
                'receipt_url' => DB::raw("REPLACE(receipt_url, 'receipts/', '')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can't easily reverse this migration as we don't know which files
        // originally had the prefix and which didn't
    }
};
