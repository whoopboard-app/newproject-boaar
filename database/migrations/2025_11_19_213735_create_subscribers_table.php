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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->enum('source', ['upload', 'online_subscribe', 'admin'])->default('online_subscribe');
            $table->enum('status', ['subscribed', 'pending_verify', 'unsubscribed', 'inactive'])->default('pending_verify');
            $table->timestamp('subscribe_date')->useCurrent();
            $table->timestamp('verify_date')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
