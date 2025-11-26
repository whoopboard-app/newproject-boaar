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
        Schema::create('knowledge_board_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('knowledge_board_id')->constrained()->onDelete('cascade');

            // Theme Type and Active Status
            $table->enum('theme_type', ['default', 'custom'])->default('default');
            $table->boolean('is_active')->default(true);

            // Header Customization
            $table->string('header_background_color', 7)->default('#11939A');
            $table->string('header_text_color', 7)->default('#FFFFFF');
            $table->string('header_intro_text', 255)->nullable();
            $table->text('header_short_description')->nullable();

            // Footer Customization
            $table->string('footer_background_color', 7)->default('#11939A');
            $table->string('footer_text_color', 7)->default('#FFFFFF');

            // SEO Fields
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();

            // Unique constraint - one theme per knowledge board
            $table->unique(['team_id', 'knowledge_board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_board_themes');
    }
};
