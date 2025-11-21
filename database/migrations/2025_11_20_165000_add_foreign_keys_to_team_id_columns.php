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

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'team_id')) {
                // Check if foreign key already exists
                $database = DB::getDatabaseName();
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
                    AND COLUMN_NAME = 'team_id'
                    AND REFERENCED_TABLE_NAME = 'teams'
                ", [$database, $tableName]);

                if (empty($foreignKeys)) {
                    Schema::table($tableName, function (Blueprint $table) {
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

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Try to drop foreign key if it exists
                    try {
                        $table->dropForeign([$tableName . '_team_id_foreign']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, ignore
                    }
                });
            }
        }
    }
};
