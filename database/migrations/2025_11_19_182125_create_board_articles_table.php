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
        Schema::create('board_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_board_id')->constrained()->onDelete('cascade');
            $table->foreignId('board_category_id')->constrained()->onDelete('cascade');
            $table->string('article_title');
            $table->longText('detailed_post');
            $table->string('cover_image')->nullable();
            $table->json('tags')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->boolean('display_as_popular')->default(false);
            $table->enum('status', ['published', 'unpublished', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_articles');
    }
};
