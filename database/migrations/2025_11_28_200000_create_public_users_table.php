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
        if (Schema::hasTable('public_users')) {
            return;
        }

        Schema::create('public_users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('full_name')->nullable();
            $table->string('magic_token')->nullable()->unique();
            $table->timestamp('magic_token_expires_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_ip')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_users');
    }
};
