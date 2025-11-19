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
        Schema::create('subscriber_user_segment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_segment_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['subscriber_id', 'user_segment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_user_segment');
    }
};
