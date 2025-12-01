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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');

            // Respondent identification
            $table->string('visitor_id')->nullable(); // Anonymous visitor ID
            $table->string('user_identifier')->nullable(); // Identified user ID/email
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('page_url')->nullable();

            // Response data
            $table->integer('score')->nullable(); // For NPS (0-10) and CSAT
            $table->text('feedback')->nullable(); // Text feedback
            $table->json('answers')->nullable(); // For quick poll/idea pool - array of selected answers
            $table->enum('category', ['promoter', 'passive', 'detractor'])->nullable(); // NPS category

            // Meta
            $table->enum('status', ['completed', 'partial', 'closed'])->default('completed');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['survey_id', 'created_at']);
            $table->index(['team_id', 'survey_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
