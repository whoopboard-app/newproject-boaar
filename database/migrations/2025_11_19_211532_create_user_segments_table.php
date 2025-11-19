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
        Schema::create('user_segments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();

            // Main attributes
            $table->json('revenue_ranges')->nullable();

            // Demographic attributes
            $table->json('locations')->nullable();
            $table->json('age_ranges')->nullable();
            $table->json('genders')->nullable();
            $table->json('languages')->nullable();

            // Behavioral & Account attributes
            $table->json('user_types')->nullable();
            $table->json('plan_types')->nullable();
            $table->json('engagement_levels')->nullable();
            $table->json('usage_frequencies')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_segments');
    }
};
