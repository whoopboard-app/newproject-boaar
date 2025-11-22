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
            if (!Schema::hasColumn('testimonial_templates', 'email_title')) {
                $table->string('email_title')->nullable()->after('enable_email_invite');
            }
            if (!Schema::hasColumn('testimonial_templates', 'email_background_color')) {
                $table->string('email_background_color')->default('#667eea')->after('email_logo');
            }
            if (!Schema::hasColumn('testimonial_templates', 'cta_button_color')) {
                $table->string('cta_button_color')->default('#667eea')->after('email_background_color');
            }
            if (!Schema::hasColumn('testimonial_templates', 'cta_button_text')) {
                $table->string('cta_button_text')->nullable()->after('cta_button_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial_templates', function (Blueprint $table) {
            if (Schema::hasColumn('testimonial_templates', 'email_title')) {
                $table->dropColumn('email_title');
            }
            if (Schema::hasColumn('testimonial_templates', 'email_background_color')) {
                $table->dropColumn('email_background_color');
            }
            if (Schema::hasColumn('testimonial_templates', 'cta_button_color')) {
                $table->dropColumn('cta_button_color');
            }
            if (Schema::hasColumn('testimonial_templates', 'cta_button_text')) {
                $table->dropColumn('cta_button_text');
            }
        });
    }
};
