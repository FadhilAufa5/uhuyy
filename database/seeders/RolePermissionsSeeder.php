<?php

namespace Database\Seeders;

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Permissions::cases() as $permission) {
            Permission::Create(['name' => $permission->value]);
        }

        foreach (Roles::cases() as $roleName) {
            $role = Role::Create(['name' => $roleName->value]);
            $this->syncPermissionsToRole($role);
        }
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = [];
        switch ($role->name) {
            case Roles::SuperAdmin->value:
                $permissions = [
                    Permissions::ManageUsers->value,
                    Permissions::ManageRoles->value,
                    Permissions::ManagePermissions->value,
                    Permissions::ManageDepartments->value,

                    Permissions::ListAssets->value,
                    Permissions::CreateAssets->value,
                    Permissions::EditAssets->value,
                    Permissions::DeleteAssets->value,

                    Permissions::ListProcurements->value,
                    Permissions::CreateProcurements->value,
                    Permissions::EditProcurements->value,
                    Permissions::DeleteProcurements->value,
                ];
                break;
            case Roles::User->value:
                $permissions = [
                    Permissions::ManageDepartments->value,
                ];
                break;
        }

        $role->syncPermissions($permissions);
    }
}
