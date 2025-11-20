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
        // Add user_id to feedback_categories
        Schema::table('feedback_categories', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
        });

        // Add user_id to roadmaps
        Schema::table('roadmaps', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
        });

        // Add user_id to feedbacks
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
        });

        // Add user_id to personas (if not already exists)
        if (!Schema::hasColumn('personas', 'user_id')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            });
        }

        // Add user_id to categories (changelog categories)
        if (!Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            });
        }

        // Add user_id to changelogs
        if (!Schema::hasColumn('changelogs', 'user_id')) {
            Schema::table('changelogs', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            });
        }

        // Add user_id to knowledge_boards
        if (!Schema::hasColumn('knowledge_boards', 'user_id')) {
            Schema::table('knowledge_boards', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            });
        }

        // Add user_id to user_segments
        if (!Schema::hasColumn('user_segments', 'user_id')) {
            Schema::table('user_segments', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained('users')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('roadmaps', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        if (Schema::hasColumn('personas', 'user_id')) {
            Schema::table('personas', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('changelogs', 'user_id')) {
            Schema::table('changelogs', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('knowledge_boards', 'user_id')) {
            Schema::table('knowledge_boards', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasColumn('user_segments', 'user_id')) {
            Schema::table('user_segments', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
