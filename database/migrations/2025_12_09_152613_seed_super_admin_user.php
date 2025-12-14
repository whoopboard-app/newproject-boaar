<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if user already exists
        $existingUser = DB::table('users')->where('email', 'admin@admin.com')->first();

        if ($existingUser) {
            // Update existing user to be super admin
            DB::table('users')
                ->where('email', 'admin@admin.com')
                ->update([
                    'is_super_admin' => true,
                    'password' => Hash::make('admin123'),
                ]);
        } else {
            // Build insert data based on available columns
            $data = [
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'is_super_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Add uuid if column exists
            if (Schema::hasColumn('users', 'uuid')) {
                $data['uuid'] = \Illuminate\Support\Str::uuid()->toString();
            }

            DB::table('users')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'admin@admin.com')->delete();
    }
};
