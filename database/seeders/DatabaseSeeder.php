<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run UserSeeder
        // $this->call(UserSeeder::class);
        $this->call(SpatiePermissionSeeder::class);
        // You can add more seeders here if needed
        // $this->call(OtherSeeder::class);
    }
}
