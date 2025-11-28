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
        Schema::create('feedback_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->boolean('enable_login_for_voting')->default(false);
            $table->boolean('enable_anonymous_voting')->default(true);
            $table->boolean('enable_email_based_voting')->default(false);
            $table->boolean('enable_internal_voting')->default(false);
            $table->integer('ip_rate_limit_per_hour')->default(10);
            $table->boolean('require_email_otp_first_vote')->default(false);
            $table->boolean('enable_device_fingerprinting')->default(false);
            $table->boolean('enable_captcha')->default(false);
            $table->timestamps();

            $table->unique('team_id'); // One feedback settings record per team
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_settings');
    }
};
