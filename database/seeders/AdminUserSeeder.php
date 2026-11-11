<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = DB::table('users')
            ->where('username', 'admin')
            ->where('role', 'admin')
            ->exists();

        if (!$adminExists) {
            DB::table('users')->insert([
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('✅ Admin user created: username=admin | password=admin123');
        } else {
            $this->command->info('⚠️  Admin user already exists');
        }
    }
}