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
        Schema::create('feedback_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('feedback_id');
            $table->unsignedBigInteger('public_user_id')->nullable(); // Null for anonymous votes
            $table->string('voter_email')->nullable(); // For email-based voting
            $table->string('voter_ip')->nullable(); // For IP tracking
            $table->string('device_fingerprint')->nullable(); // For device tracking
            $table->timestamp('voted_at');
            $table->timestamps();

            $table->foreign('feedback_id')->references('id')->on('feedback')->onDelete('cascade');
            $table->foreign('public_user_id')->references('id')->on('public_users')->onDelete('cascade');

            // Ensure one vote per user per feedback (for authenticated users)
            $table->unique(['feedback_id', 'public_user_id'], 'unique_feedback_user_vote');
        });

        // Add index for IP-based rate limiting queries
        Schema::table('feedback_votes', function (Blueprint $table) {
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
