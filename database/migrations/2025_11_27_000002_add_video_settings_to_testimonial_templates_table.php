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
            $table->integer('max_video_duration')->default(120)->after('collect_video'); // in seconds
            $table->boolean('allow_video_retake')->default(true)->after('max_video_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial_templates', function (Blueprint $table) {
            $table->dropColumn(['max_video_duration', 'allow_video_retake']);
        });
    }
};
