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
        Schema::dropIfExists('article_ratings');

        Schema::create('article_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('rateable_type'); // 'changelog' or 'article'
            $table->unsignedBigInteger('rateable_id'); // changelog_id or article_id
            $table->string('rating_value'); // 'yes', 'no', '1-5' for emoji/star, '1-10' for numeric
            $table->string('rating_type'); // 'yes_no', 'emoji', 'star', 'numeric', 'comment_only'
            $table->text('comment')->nullable();
            $table->string('voter_ip')->nullable();
            $table->string('voter_fingerprint')->nullable();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->index(['rateable_type', 'rateable_id']);
            $table->index(['team_id', 'rateable_type', 'rateable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_ratings');
    }
};
