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
        Schema::table('testimonials', function (Blueprint $table) {
            // Mux video fields
            $table->string('mux_upload_id')->nullable()->after('video_thumbnail');
            $table->string('mux_asset_id')->nullable()->after('mux_upload_id');
            $table->string('mux_playback_id')->nullable()->after('mux_asset_id');
            $table->enum('mux_status', ['waiting', 'uploading', 'processing', 'ready', 'error'])->nullable()->after('mux_playback_id');
            $table->integer('video_duration')->nullable()->after('mux_status'); // Duration in seconds
            $table->json('mux_data')->nullable()->after('video_duration'); // Store additional Mux metadata
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn([
                'mux_upload_id',
                'mux_asset_id',
                'mux_playback_id',
                'mux_status',
                'video_duration',
                'mux_data',
            ]);
        });
    }
};
