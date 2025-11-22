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
            if (!Schema::hasColumn('testimonial_templates', 'page_background_color')) {
                $table->string('page_background_color')->default('#667eea')->after('thankyou_offer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial_templates', function (Blueprint $table) {
            if (Schema::hasColumn('testimonial_templates', 'page_background_color')) {
                $table->dropColumn('page_background_color');
            }
        });
    }
};
