<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\Permissions as PermissionEnum;
use App\Enums\Roles;

class UpdateRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions if they don't exist
        $permissions = [
            PermissionEnum::ManageUsers->value,
            PermissionEnum::ManageRoles->value,
            PermissionEnum::ManagePermissions->value,
            PermissionEnum::ManageDepartments->value,
            PermissionEnum::ListVendors->value,
            PermissionEnum::CreateVendors->value,
            PermissionEnum::EditVendors->value,
            PermissionEnum::DeleteVendors->value,
            PermissionEnum::ListAssets->value,
            PermissionEnum::CreateAssets->value,
            PermissionEnum::EditAssets->value,
            PermissionEnum::DeleteAssets->value,
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create/Update Super Admin role with all permissions
        $superAdmin = Role::firstOrCreate(['name' => Roles::SuperAdmin->value]);
        $superAdmin->syncPermissions($permissions);

        // Create/Update User role with limited permissions
        // User can only access: Dashboard & Data Management (branches, apoteks)
        $userRole = Role::firstOrCreate(['name' => Roles::User->value]);
        $userRole->syncPermissions([
            PermissionEnum::ManageDepartments->value, // Access to branches & apoteks
        ]);

        // Vendor role (keep existing)
        $vendorRole = Role::firstOrCreate(['name' => Roles::Vendor->value]);
        // Vendors have their own specific permissions, don't change

        // Delete old Manager role if exists
        Role::where('name', 'manager')->delete();

        $this->command->info('✅ Roles and Permissions updated successfully!');
        $this->command->info('   - Super Admin: Full access to all features');
        $this->command->info('   - User: Access to Dashboard & Data Management only');
        $this->command->info('   - Vendor: Vendor-specific permissions');
    }
}
