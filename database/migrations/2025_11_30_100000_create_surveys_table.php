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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['nps', 'csat', 'open_feedback', 'quick_poll', 'idea_pool']);
            $table->enum('status', ['draft', 'published', 'paused'])->default('draft');

            // General Settings (varies by type)
            $table->json('general_settings')->nullable();

            // Trigger Settings
            $table->enum('trigger_type', ['page_load', 'page_leave', 'code_trigger', 'scroll'])->default('page_load');
            $table->integer('trigger_delay_seconds')->default(0);
            $table->enum('show_survey_timing', ['first_visit', 'after_period'])->default('first_visit');
            $table->integer('show_after_value')->nullable();
            $table->enum('show_after_unit', ['days', 'hours', 'months'])->nullable();

            // Location Settings
            $table->enum('location_type', ['all_pages', 'specific_pages'])->default('all_pages');
            $table->json('location_urls')->nullable(); // Array of {type: 'show'|'hide', url: string}

            // Audience Settings
            $table->boolean('only_identified_users')->default(false);
            $table->json('segment_ids')->nullable(); // Array of segment IDs

            // Frequency Settings
            $table->enum('on_response_action', ['never_show', 'show_after'])->default('never_show');
            $table->integer('on_response_show_after_value')->nullable();
            $table->enum('on_response_show_after_unit', ['days', 'hours', 'months'])->nullable();
            $table->enum('on_close_action', ['never_show', 'show_after'])->default('show_after');
            $table->integer('on_close_show_after_value')->default(1);
            $table->enum('on_close_show_after_unit', ['days', 'hours', 'months'])->default('days');
            $table->boolean('skip_if_answered_in_session')->default(false);

            // Appearance Settings
            $table->enum('theme', ['inherit', 'light', 'dark', 'purple', 'navy'])->default('inherit');
            $table->string('accent_color', 7)->default('#5865F2');
            $table->boolean('hide_branding')->default(false);
            $table->enum('position', ['bottom_left', 'bottom_right', 'bottom_center', 'center'])->default('bottom_right');

            // NPS Specific Labels
            $table->string('label_least_likely')->default('Not at all likely');
            $table->string('label_most_likely')->default('Extremely likely');
            $table->string('label_back_button')->default('Back');
            $table->string('label_skip_button')->default('Skip');
            $table->string('label_submit_button')->default('Submit');
            $table->string('feedback_question')->default('What is the main reason for your score?');
            $table->string('feedback_placeholder')->default('Your feedback helps us improve...');
            $table->string('feedback_thank_you')->default('Thank you for your feedback!');

            // Feedback form option
            $table->enum('feedback_form_option', ['optional', 'required', 'dont_show'])->default('optional');
            $table->boolean('show_labels')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
