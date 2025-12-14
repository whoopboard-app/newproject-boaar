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
        // Create teams table
        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Add current_team_id and role to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_team_id')) {
                $table->unsignedBigInteger('current_team_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('member')->after('current_team_id'); // owner, admin, moderator, idea_submitter, viewer
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->nullable()->after('avatar');
            }
        });

        // Add foreign key if current_team_id exists and foreign key doesn't exist
        if (Schema::hasColumn('users', 'current_team_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('current_team_id')->references('id')->on('teams')->nullOnDelete();
                });
            } catch (\Exception $e) {
                // Foreign key may already exist
            }
        }

        // Create team_user pivot table for team membership
        if (!Schema::hasTable('team_user')) {
            Schema::create('team_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('role')->default('member'); // owner, admin, moderator, idea_submitter, viewer
                $table->timestamps();

                $table->unique(['team_id', 'user_id']);
            });
        }

        // Create team invitations table
        if (!Schema::hasTable('team_invitations')) {
            Schema::create('team_invitations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
                $table->foreignId('invited_by')->constrained('users')->cascadeOnDelete();
                $table->string('email');
                $table->string('role'); // admin, moderator, idea_submitter, viewer
                $table->string('token')->unique();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
        Schema::dropIfExists('team_user');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_team_id']);
            $table->dropColumn(['current_team_id', 'role', 'avatar', 'timezone']);
        });

        Schema::dropIfExists('teams');
    }
};
