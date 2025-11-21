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
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $table) {
                    // Drop old user_id foreign key and column
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                });

                // Only add team_id if it doesn't already exist
                if (!Schema::hasColumn($table, 'team_id')) {
                    Schema::table($table, function (Blueprint $table) {
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

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['team_id']);
                    $table->dropColumn('team_id');
                });

                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
                });
            }
        }
    }
};
