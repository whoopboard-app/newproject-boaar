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
            if (!Schema::hasColumn('testimonial_templates', 'form_background_color')) {
                $table->string('form_background_color')->default('#667eea')->after('page_background_color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial_templates', function (Blueprint $table) {
            if (Schema::hasColumn('testimonial_templates', 'form_background_color')) {
                $table->dropColumn('form_background_color');
            }
        });
    }
};
