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
        if (Schema::hasTable('feedback_votes')) {
            return;
        }

        Schema::create('feedback_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('feedbacks')->onDelete('cascade');
            $table->foreignId('public_user_id')->nullable()->constrained('public_users')->onDelete('cascade');
            $table->string('voter_email')->nullable(); // For email-based voting
            $table->string('voter_ip')->nullable(); // For IP tracking
            $table->string('device_fingerprint')->nullable(); // For device tracking
            $table->timestamp('voted_at');
            $table->timestamps();

            $table->unique(['feedback_id', 'public_user_id'], 'unique_feedback_user_vote');
            $table->index(['voter_ip', 'voted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_votes');
    }
};
