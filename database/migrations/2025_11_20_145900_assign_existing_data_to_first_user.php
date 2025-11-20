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
        // Get the first user's ID
        $firstUserId = DB::table('users')->orderBy('id')->value('id');

        if (!$firstUserId) {
            return; // No users exist, skip
        }

        // Assign existing data to the first user
        $tables = [
            'categories',
            'changelogs',
            'knowledge_boards',
            'personas',
            'user_segments',
            'roadmaps',
            'feedback_categories',
            'feedbacks',
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                // Only update records that don't have a user_id yet
                DB::table($table)
                    ->whereNull('user_id')
                    ->update(['user_id' => $firstUserId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration
    }
};
