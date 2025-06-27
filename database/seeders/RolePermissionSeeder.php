<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $agentRole = Role::firstOrCreate(['name' => 'agent']);

        // Define permissions
        $permissions = [
            'manage users',
            'view dashboard',
            'purchase plan',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo('purchase plan');
        $agentRole->givePermissionTo('purchase plan');
    }
} 