<?php

namespace App\Enums;

enum Permissions : string
{
    case ManageUsers = 'manage-users';
    case ManageRoles = 'manage-roles';
    case ManagePermissions = 'manage-permissions';
    case ManageDepartments = 'manage-departments';

    case ListAssets = 'list-assets';
    case CreateAssets = 'create-assets';
    case EditAssets = 'edit-assets';
    case DeleteAssets = 'delete-assets';

    case ListProcurements = 'list-procurements';
    case CreateProcurements = 'create-procurements';
    case EditProcurements = 'edit-procurements';
    case DeleteProcurements = 'delete-procurements';

    public static function labels(): array
    {
        return [
            self::ManageUsers->value => 'Manage Users',
            self::ManageRoles->value => 'Manage Roles',
            self::ManagePermissions->value => 'Manage Permissions',
            self::ManageDepartments->value => 'Manage Departments',

            self::ListAssets->value => 'List Assets',
            self::CreateAssets->value => 'Create Assets',
            self::EditAssets->value => 'Edit Assets',
            self::DeleteAssets->value => 'Delete Assets',

            self::ListProcurements->value => 'List Procurements',
            self::CreateProcurements->value => 'Create Procurements',
            self::EditProcurements->value => 'Edit Procurements',
            self::DeleteProcurements->value => 'Delete Procurements',
        ];
    }
    public function label(): string
    {
        return match ($this) {
            self::ManageUsers => 'Manage Users',
            self::ManageRoles => 'Manage Roles',
            self::ManagePermissions => 'Manage Permissions',
            self::ManageDepartments => 'Manage Departments',

            self::ListAssets => 'List Assets',
            self::CreateAssets => 'Create Assets',
            self::EditAssets => 'Edit Assets',
            self::DeleteAssets => 'Delete Assets',

            self::ListProcurements => 'List Procurements',
            self::CreateProcurements => 'Create Procurements',
            self::EditProcurements => 'Edit Procurements',
            self::DeleteProcurements => 'Delete Procurements',
        };
    }
}
