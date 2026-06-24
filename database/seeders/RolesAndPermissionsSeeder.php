<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $hodRole = Role::create(['name' => 'HOD']);
        $financeRole = Role::create(['name' => 'Finance Officer']);
        $auditorRole = Role::create(['name' => 'Auditor']);
        $studentRole = Role::create(['name' => 'Student']);

        // Since this is a basic setup, we'll assign full privileges to Admin
        // More granular permissions can be added later
    }
}
