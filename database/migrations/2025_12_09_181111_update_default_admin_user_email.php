<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing admin user or insert new one
        $existingAdmin = DB::table('admin_users')->where('email', 'admin@admin.com')->first();

        if ($existingAdmin) {
            DB::table('admin_users')
                ->where('email', 'admin@admin.com')
                ->update([
                    'email' => 'rohitcphilip@gmail.com',
                    'password' => Hash::make('admin123'),
                    'updated_at' => now(),
                ]);
        } else {
            // Insert if no admin exists
            DB::table('admin_users')->insertOrIgnore([
                'name' => 'Super Admin',
                'email' => 'rohitcphilip@gmail.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('admin_users')
            ->where('email', 'rohitcphilip@gmail.com')
            ->update([
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'updated_at' => now(),
            ]);
    }
};
