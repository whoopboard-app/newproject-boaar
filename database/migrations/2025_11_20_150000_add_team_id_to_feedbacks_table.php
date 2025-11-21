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
        Schema::table('feedbacks', function (Blueprint $table) {
            if (!Schema::hasColumn('feedbacks', 'team_id')) {
                // Add team_id column without foreign key constraint
                // Foreign key will be added later after teams table is created
                $table->unsignedBigInteger('team_id')->after('id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {

            // Drop foreign key if exists
            if (Schema::hasColumn('feedbacks', 'team_id')) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            }
        });
    }
};
