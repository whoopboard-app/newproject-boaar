<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum values for status column
        DB::statement("ALTER TABLE testimonials MODIFY COLUMN status ENUM('published', 'pending_review', 'under_review', 'draft', 'active', 'inactive') DEFAULT 'pending_review'");

        // Update existing records: 'active' -> 'published', others stay the same
        DB::table('testimonials')
            ->where('status', 'active')
            ->update(['status' => 'published']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values
        DB::table('testimonials')
            ->where('status', 'published')
            ->update(['status' => 'active']);

        DB::statement("ALTER TABLE testimonials MODIFY COLUMN status ENUM('active', 'inactive', 'draft') DEFAULT 'draft'");
    }
};
