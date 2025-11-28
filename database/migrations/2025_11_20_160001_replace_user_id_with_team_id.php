<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if a foreign key exists on a table.
     */
    private function foreignKeyExists(string $tableName, string $columnName): bool
    {
        $database = DB::getDatabaseName();
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND COLUMN_NAME = ?
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$database, $tableName, $columnName]);

        return !empty($foreignKeys);
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
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

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'user_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Drop old user_id foreign key if it exists
                    if ($this->foreignKeyExists($tableName, 'user_id')) {
                        $table->dropForeign(['user_id']);
                    }
                    $table->dropColumn('user_id');
                });

                // Only add team_id if it doesn't already exist
                if (!Schema::hasColumn($tableName, 'team_id')) {
                    Schema::table($tableName, function (Blueprint $table) {
                        // Add team_id foreign key
                        $table->foreignId('team_id')->after('id')->constrained('teams')->cascadeOnDelete();
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
            'categories',
            'changelogs',
            'knowledge_boards',
            'personas',
            'user_segments',
            'roadmaps',
            'feedback_categories',
            'feedbacks',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'team_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if ($this->foreignKeyExists($tableName, 'team_id')) {
                        $table->dropForeign(['team_id']);
                    }
                    $table->dropColumn('team_id');
                });

                if (!Schema::hasColumn($tableName, 'user_id')) {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
                    });
                }
            }
        }
    }
};
