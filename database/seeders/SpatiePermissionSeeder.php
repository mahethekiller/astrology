<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SpatiePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Spatie Permission Seeder...');

        // Check if permissions table exists
        if (!Schema::hasTable('permissions')) {
            $this->command->error('Permissions table does not exist! Please run migrations first.');
            $this->command->info('Run: php artisan migrate');
            return;
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('✅ Permissions created successfully!');

        // Create roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'web'
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

        $this->command->info('✅ Roles created successfully!');

        // Assign all permissions to admin
        $adminRole->syncPermissions(Permission::all());

        // Assign limited permissions to manager
        $managerPermissions = Permission::whereIn('name', [
            'view-users', 'create-users', 'edit-users',
            'view-content', 'create-content', 'edit-content', 'delete-content'
        ])->get();

        $managerRole->syncPermissions($managerPermissions);

        // Assign basic permissions to user
        $userPermissions = Permission::whereIn('name', ['view-content'])->get();
        $userRole->syncPermissions($userPermissions);

        $this->command->info('✅ Permissions assigned to roles successfully!');

        // Create or update users with roles
        $this->createUsersWithRoles();

        $this->command->info('🎉 Spatie Permission system setup completed!');
        $this->command->info('================================');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Manager: manager@example.com / password');
        $this->command->info('User: user@example.com / password');
        $this->command->info('================================');
    }

    private function createUsersWithRoles(): void
    {
        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');
        $this->command->info('✅ Admin user created/updated');

        // Create manager user
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $managerUser->assignRole('manager');
        $this->command->info('✅ Manager user created/updated');

        // Create regular user
        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $regularUser->assignRole('user');
        $this->command->info('✅ Regular user created/updated');
    }
}
