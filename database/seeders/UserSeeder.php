<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting User Seeder (Spatie Compatible)...');

        // Create users without the 'role' column
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],

            [
                'name' => 'Astrologer User',
                'email' => 'astrologer@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],

        ];

        $createdCount = 0;

        foreach ($users as $user) {
            try {
                // Remove 'role' from the array if it exists
                unset($user['role']);

                // Check if user already exists
                $existingUser = User::where('email', $user['email'])->first();

                if ($existingUser) {
                    $this->command->warn("User {$user['email']} already exists. Updating...");
                    // Update without role field
                    unset($user['role']);
                    $existingUser->update($user);
                } else {
                    User::create($user);
                    $createdCount++;
                    $this->command->info("✅ Created user: {$user['email']}");
                }
            } catch (\Exception $e) {
                $this->command->error("Error creating user {$user['email']}: " . $e->getMessage());
            }
        }

        $this->command->info("🎉 User Seeder completed! Created: {$createdCount} users");
        $this->command->info('--------------------------------');
        $this->command->info('Note: Roles will be assigned by SpatiePermissionSeeder');
        $this->command->info('--------------------------------');
    }
}
