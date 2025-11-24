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
        Schema::table('campaign_subscribers', function (Blueprint $table) {
            $table->boolean('email_failed')->default(false)->after('sent_at');
            $table->text('failure_reason')->nullable()->after('email_failed');
            $table->integer('send_attempts')->default(0)->after('failure_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_subscribers', function (Blueprint $table) {
            $table->dropColumn(['email_failed', 'failure_reason', 'send_attempts']);
        });
    }
};
