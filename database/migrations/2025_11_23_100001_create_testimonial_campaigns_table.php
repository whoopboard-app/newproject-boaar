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
        Schema::create('testimonial_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('testimonial_templates')->nullOnDelete();
            $table->string('name');
            $table->text('objective')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->enum('delivery_type', ['instant', 'scheduled'])->default('instant');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->integer('subscribers_sent')->default(0);
            $table->integer('emails_opened')->default(0);
            $table->integer('emails_clicked')->default(0);
            $table->integer('testimonials_collected')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_campaigns');
    }
};
