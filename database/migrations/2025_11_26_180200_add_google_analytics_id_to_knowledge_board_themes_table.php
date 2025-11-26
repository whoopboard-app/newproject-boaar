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
        Schema::table('knowledge_board_themes', function (Blueprint $table) {
            if (!Schema::hasColumn('knowledge_board_themes', 'google_analytics_id')) {
                $table->string('google_analytics_id', 50)->nullable()->after('meta_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_board_themes', function (Blueprint $table) {
            if (Schema::hasColumn('knowledge_board_themes', 'google_analytics_id')) {
                $table->dropColumn('google_analytics_id');
            }
        });
    }
};
