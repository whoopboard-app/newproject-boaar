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
        // Add foreign key constraints to team_id columns after teams table exists
        $tables = [
            'feedbacks',
            'categories',
            'changelogs',
            'knowledge_boards',
            'personas',
            'user_segments',
            'roadmaps',
            'feedback_categories',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'team_id')) {
                // Check if foreign key already exists
                $foreignKeys = DB::select(DB::raw("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = '{$table}'
                    AND COLUMN_NAME = 'team_id'
                    AND REFERENCED_TABLE_NAME = 'teams'
                "));

                if (empty($foreignKeys)) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'feedbacks',
            'categories',
            'changelogs',
            'knowledge_boards',
            'personas',
            'user_segments',
            'roadmaps',
            'feedback_categories',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($table) {
                    // Try to drop foreign key if it exists
                    try {
                        $table->dropForeign(['{$table}_team_id_foreign']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, ignore
                    }
                });
            }
        }
    }
};
