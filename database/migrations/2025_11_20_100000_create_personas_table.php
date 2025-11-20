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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Tech-Savvy Sarah"
            $table->string('avatar')->nullable(); // Profile image
            $table->string('role'); // e.g., "Marketing Manager"
            $table->string('age_range')->nullable(); // e.g., "30-35"
            $table->string('location')->nullable(); // e.g., "San Francisco, CA"
            $table->text('description'); // Brief overview
            $table->json('goals')->nullable(); // Array of goals
            $table->json('pain_points')->nullable(); // Array of pain points
            $table->json('behaviors')->nullable(); // Array of behaviors
            $table->text('quote')->nullable(); // Representative quote
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Pivot table for persona-segment relationships
        Schema::create('persona_user_segment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_segment_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['persona_id', 'user_segment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_user_segment');
        Schema::dropIfExists('personas');
    }
};
