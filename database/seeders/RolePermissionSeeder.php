<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles for both 'web' and 'api' guards
        foreach (['web', 'api'] as $guard) {
            $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
            $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => $guard]);
            $agentRole = Role::firstOrCreate(['name' => 'agent', 'guard_name' => $guard]);
        }

        // Define permissions
        $permissions = [
            'manage users',
            'view dashboard',
            'purchase plan',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
        }

        // Assign permissions to roles for both guards
        foreach (['web', 'api'] as $guard) {
            $adminRole = Role::where('name', 'admin')->where('guard_name', $guard)->first();
            $userRole = Role::where('name', 'user')->where('guard_name', $guard)->first();
            $agentRole = Role::where('name', 'agent')->where('guard_name', $guard)->first();
            $adminRole->givePermissionTo(Permission::where('guard_name', $guard)->pluck('name')->toArray());
            $userRole->givePermissionTo('purchase plan');
            $agentRole->givePermissionTo('purchase plan');
        }
    }
} 