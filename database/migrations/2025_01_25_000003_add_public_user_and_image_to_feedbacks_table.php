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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('public_user_id')->nullable()->after('persona_id');
            $table->string('image')->nullable()->after('tags');
            $table->string('verification_token')->nullable()->unique()->after('source');
            $table->boolean('is_verified')->default(false)->after('verification_token');
            $table->timestamp('verified_at')->nullable()->after('is_verified');

            $table->foreign('public_user_id')->references('id')->on('public_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['public_user_id']);
            $table->dropColumn(['public_user_id', 'image', 'verification_token', 'is_verified', 'verified_at']);
        });
    }
};
