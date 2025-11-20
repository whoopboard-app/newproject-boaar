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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->text('idea');
            $table->foreignId('feedback_category_id')->nullable()->constrained('feedback_categories')->nullOnDelete();
            $table->text('value_description')->nullable();
            $table->foreignId('roadmap_id')->nullable()->constrained('roadmaps')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->boolean('login_access_enabled')->default(false);
            $table->json('tags')->nullable();
            $table->foreignId('persona_id')->nullable()->constrained('personas')->nullOnDelete();
            $table->enum('source', ['Admin Added', 'User Submitted', 'Social Scraping', 'Project Management tool', 'Support System'])->default('Admin Added');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
