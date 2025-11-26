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
            if (!Schema::hasColumn('knowledge_board_themes', 'menu_order')) {
                $table->json('menu_order')->nullable()->after('google_analytics_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge_board_themes', function (Blueprint $table) {
            if (Schema::hasColumn('knowledge_board_themes', 'menu_order')) {
                $table->dropColumn('menu_order');
            }
        });
    }
};
