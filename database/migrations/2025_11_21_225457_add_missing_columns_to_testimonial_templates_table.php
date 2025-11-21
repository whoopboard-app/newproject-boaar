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
        Schema::table('testimonial_templates', function (Blueprint $table) {
            // Add columns only if they don't exist
            if (!Schema::hasColumn('testimonial_templates', 'enable_email_invite')) {
                $table->boolean('enable_email_invite')->default(false)->after('unique_url');
            }
            if (!Schema::hasColumn('testimonial_templates', 'email_subject')) {
                $table->string('email_subject')->nullable()->after('enable_email_invite');
            }
            if (!Schema::hasColumn('testimonial_templates', 'email_content')) {
                $table->text('email_content')->nullable()->after('email_subject');
            }
            if (!Schema::hasColumn('testimonial_templates', 'email_logo')) {
                $table->string('email_logo')->nullable()->after('email_content');
            }
            if (!Schema::hasColumn('testimonial_templates', 'promotional_offer')) {
                $table->string('promotional_offer')->nullable()->after('email_logo');
            }

            // Form Fields
            if (!Schema::hasColumn('testimonial_templates', 'field_full_name')) {
                $table->boolean('field_full_name')->default(false)->after('promotional_offer');
            }
            if (!Schema::hasColumn('testimonial_templates', 'field_first_name')) {
                $table->boolean('field_first_name')->default(false)->after('field_full_name');
            }
            if (!Schema::hasColumn('testimonial_templates', 'field_last_name')) {
                $table->boolean('field_last_name')->default(false)->after('field_first_name');
            }
            if (!Schema::hasColumn('testimonial_templates', 'field_email')) {
                $table->boolean('field_email')->default(true)->after('field_last_name');
            }
            if (!Schema::hasColumn('testimonial_templates', 'field_company')) {
                $table->boolean('field_company')->default(false)->after('field_email');
            }
            if (!Schema::hasColumn('testimonial_templates', 'field_position')) {
                $table->boolean('field_position')->default(false)->after('field_company');
            }
            if (!Schema::hasColumn('testimonial_templates', 'field_social_url')) {
                $table->boolean('field_social_url')->default(false)->after('field_position');
            }

            // Testimonial Type
            if (!Schema::hasColumn('testimonial_templates', 'collect_text')) {
                $table->boolean('collect_text')->default(true)->after('field_social_url');
            }
            if (!Schema::hasColumn('testimonial_templates', 'collect_video')) {
                $table->boolean('collect_video')->default(false)->after('collect_text');
            }
            if (!Schema::hasColumn('testimonial_templates', 'collect_rating')) {
                $table->boolean('collect_rating')->default(true)->after('collect_video');
            }
            if (!Schema::hasColumn('testimonial_templates', 'rating_style')) {
                $table->string('rating_style')->default('star')->after('collect_rating');
            }

            // Thank You Page
            if (!Schema::hasColumn('testimonial_templates', 'enable_thankyou')) {
                $table->boolean('enable_thankyou')->default(false)->after('rating_style');
            }
            if (!Schema::hasColumn('testimonial_templates', 'thankyou_title')) {
                $table->string('thankyou_title')->nullable()->after('enable_thankyou');
            }
            if (!Schema::hasColumn('testimonial_templates', 'thankyou_description')) {
                $table->text('thankyou_description')->nullable()->after('thankyou_title');
            }
            if (!Schema::hasColumn('testimonial_templates', 'thankyou_offer')) {
                $table->string('thankyou_offer')->nullable()->after('thankyou_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial_templates', function (Blueprint $table) {
            $columns = [
                'enable_email_invite', 'email_subject', 'email_content', 'email_logo', 'promotional_offer',
                'field_full_name', 'field_first_name', 'field_last_name', 'field_email',
                'field_company', 'field_position', 'field_social_url',
                'collect_text', 'collect_video', 'collect_rating', 'rating_style',
                'enable_thankyou', 'thankyou_title', 'thankyou_description', 'thankyou_offer'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('testimonial_templates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
