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
        Schema::create('knowledge_boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('short_description');
            $table->string('cover_page')->nullable();
            $table->enum('document_type', ['manual', 'help_document'])->default('manual');
            $table->enum('visibility_type', ['private', 'public'])->default('private');
            $table->enum('status', ['published', 'unpublished', 'draft'])->default('draft');
            $table->foreignId('board_owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('has_public_url')->default(false);
            $table->string('public_url')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_boards');
    }
};
