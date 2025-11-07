<?php

namespace App\Enums;

enum BusinessTypes: string
{
    case LocalCompany = 'local-cpy';
    case LocalIndividual = 'local-ind';
    case InternationalCompany = 'inter-cpy';
    case InternationalIndividual = 'inter-ind';

    public static function labels(): array
    {
        return [
            self::LocalCompany->value => 'Local Vendor-Company',
            self::LocalIndividual->value => 'Local Vendor-Individual',
            self::InternationalCompany->value => 'International Vendor-Company',
            self::InternationalIndividual->value => 'International Vendor-Individual',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::LocalCompany => 'Local Vendor-Company',
            self::LocalIndividual => 'Local Vendor-Individual',
            self::InternationalCompany => 'International Vendor-Company',
            self::InternationalIndividual => 'International Vendor-Individual',
        };
    }

}
