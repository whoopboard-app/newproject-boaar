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
        Schema::create('testimonial_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('name');
            $table->string('unique_url')->unique();

            // Email Invite Settings
            $table->boolean('enable_email_invite')->default(false);
            $table->string('email_subject')->nullable();
            $table->text('email_content')->nullable();
            $table->string('email_logo')->nullable();
            $table->string('promotional_offer')->nullable();

            // Form Fields
            $table->boolean('field_full_name')->default(false);
            $table->boolean('field_first_name')->default(false);
            $table->boolean('field_last_name')->default(false);
            $table->boolean('field_email')->default(true);
            $table->boolean('field_company')->default(false);
            $table->boolean('field_position')->default(false);
            $table->boolean('field_social_url')->default(false);

            // Testimonial Type
            $table->boolean('collect_text')->default(true);
            $table->boolean('collect_video')->default(false);
            $table->boolean('collect_rating')->default(true);
            $table->string('rating_style')->default('star'); // star or smile

            // Thank You Page
            $table->boolean('enable_thankyou')->default(false);
            $table->string('thankyou_title')->nullable();
            $table->text('thankyou_description')->nullable();
            $table->string('thankyou_offer')->nullable();

            $table->text('welcome_message')->nullable();
            $table->text('thank_you_message')->nullable();
            $table->json('custom_fields')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_templates');
    }
};
