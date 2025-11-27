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
        Schema::table('categories', function (Blueprint $table) {
            // Drop the existing unique constraints on name and slug
            $table->dropUnique(['name']);
            $table->dropUnique(['slug']);

            // Add new composite unique constraints with team_id
            $table->unique(['team_id', 'name']);
            $table->unique(['team_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the composite unique constraints
            $table->dropUnique(['team_id', 'name']);
            $table->dropUnique(['team_id', 'slug']);

            // Restore the original unique constraints
            $table->unique('name');
            $table->unique('slug');
        });
    }
};
