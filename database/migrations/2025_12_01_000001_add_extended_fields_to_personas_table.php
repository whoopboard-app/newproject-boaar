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
        Schema::table('personas', function (Blueprint $table) {
            // Persona Header
            $table->string('tagline')->nullable()->after('name');
            $table->json('segmentation_tags')->nullable()->after('tagline');

            // Identity
            $table->string('gender')->nullable()->after('age_range');
            $table->string('occupation')->nullable()->after('location');
            $table->string('education')->nullable()->after('occupation');
            $table->string('income')->nullable()->after('education');

            // Goals & Pain Points - using existing goals and pain_points columns
            // Adding motivations field
            $table->json('motivations')->nullable()->after('goals');
            $table->json('frustrations')->nullable()->after('pain_points');

            // Behavior
            $table->json('device_usage')->nullable()->after('behaviors');
            $table->json('channel_preference')->nullable()->after('device_usage');
            $table->text('workflow')->nullable()->after('channel_preference');
            $table->text('buying_behavior')->nullable()->after('workflow');

            // Product Fit
            $table->string('journey_stage')->nullable()->after('buying_behavior');
            $table->json('modules_interacted')->nullable()->after('journey_stage');
            $table->json('weighted_impact_scores')->nullable()->after('modules_interacted');

            // Persona Summary Card
            $table->text('bio')->nullable()->after('weighted_impact_scores');
            // quote already exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn([
                'tagline',
                'segmentation_tags',
                'gender',
                'occupation',
                'education',
                'income',
                'motivations',
                'frustrations',
                'device_usage',
                'channel_preference',
                'workflow',
                'buying_behavior',
                'journey_stage',
                'modules_interacted',
                'weighted_impact_scores',
                'bio',
            ]);
        });
    }
};
