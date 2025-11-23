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
        Schema::create('campaign_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('testimonial_campaigns')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->constrained('subscribers')->cascadeOnDelete();
            $table->string('tracking_token')->unique();
            $table->boolean('email_sent')->default(false);
            $table->dateTime('sent_at')->nullable();
            $table->boolean('email_opened')->default(false);
            $table->dateTime('opened_at')->nullable();
            $table->boolean('link_clicked')->default(false);
            $table->dateTime('clicked_at')->nullable();
            $table->boolean('testimonial_submitted')->default(false);
            $table->foreignId('testimonial_id')->nullable()->constrained('testimonials')->nullOnDelete();
            $table->timestamps();

            $table->unique(['campaign_id', 'subscriber_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_subscribers');
    }
};
