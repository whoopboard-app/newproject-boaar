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
        Schema::create('roadmap_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('feedback_id')->nullable()->constrained('feedbacks')->onDelete('set null');
            $table->string('idea');
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->string('external_pm_tool_id')->nullable();
            $table->foreignId('roadmap_status_id')->nullable()->constrained('roadmaps')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_items');
    }
};
