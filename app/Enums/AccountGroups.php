<?php

namespace App\Enums;

enum AccountGroups: string
{
    case LocalVendor = 'local';
    case AffiliatedVendor = 'affilated';
    case ForeignVendor = 'foreign';
    case OtherVendor = 'other';
    case EmployeeVendor = 'employee';
    case Manufacturer = 'manufacturer';
    case Principle = 'principle';

    public static function labels(): array
    {
        return [
            self::LocalVendor->value => 'Local Vendor',
            self::AffiliatedVendor->value => 'Affiliated Vendor',
            self::ForeignVendor->value =>  'Foreign Vendor',
            self::OtherVendor->value => 'Other Vendor',
            self::EmployeeVendor->value => 'Employee Vendor',
            self::Manufacturer->value => 'Manufacturer',
            self::Principle->value => 'Principle',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::LocalVendor => 'Local Vendor',
            self::AffiliatedVendor => 'Affiliated Vendor',
            self::ForeignVendor => 'Foreign Vendor',
            self::OtherVendor => 'Other Vendor',
            self::EmployeeVendor => 'Employee Vendor',
            self::Manufacturer => 'Manufacturer',
            self::Principle => 'Principle',
        };
    }

}
