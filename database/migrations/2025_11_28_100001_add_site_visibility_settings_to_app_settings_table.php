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
        Schema::table('app_settings', function (Blueprint $table) {
            // Site Visibility Settings
            $table->enum('site_visibility', ['public', 'private'])->default('public')->after('block_search_indexing');

            // Maintenance Mode Settings
            $table->boolean('maintenance_mode')->default(false)->after('site_visibility');
            $table->timestamp('maintenance_scheduled_at')->nullable()->after('maintenance_mode');
            $table->timestamp('maintenance_ends_at')->nullable()->after('maintenance_scheduled_at');
            $table->string('maintenance_message')->nullable()->after('maintenance_ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_visibility',
                'maintenance_mode',
                'maintenance_scheduled_at',
                'maintenance_ends_at',
                'maintenance_message',
            ]);
        });
    }
};
