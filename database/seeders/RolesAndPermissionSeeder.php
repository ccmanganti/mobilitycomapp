<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// This seeder will be used to seed Roles and Permissions for the App
// Custom Imports
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Defining all permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view gloves',
            'create gloves',
            'edit gloves',
            'delete gloves',
            'view actions',
            'create actions',
            'edit actions',
            'delete actions',
            'view readings',
            'create readings',
            'edit readings',
            'delete readings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'Admin' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
                'view gloves',
                'create gloves',
                'edit gloves',
                'delete gloves',
                'view actions',
                'create actions',
                'edit actions',
                'delete actions',
                'view readings',
                'create readings',
                'edit readings',
                'delete readings'
            ],
            'User' => [
                'view users',
                'delete users',
                'view gloves',
                'create gloves',
                'edit gloves',
                'delete gloves',
                'view actions',
                'create actions',
                'edit actions',
                'delete actions',
                'view readings',
                'create readings',
                'edit readings',
                'delete readings'
            ],
        ];

        foreach ($roles as $role => $perms) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($perms);
        }
    }
}
