<?php

namespace App\Enums;

enum BusinessEntities: string
{
    case PerseroanTerbatas = 'pt';
    case CommanditaireVennootschap = 'cv';
    case Koperasi = 'co';
    case Firma = 'fm';

    public static function labels(): array
    {
        return [
            self::PerseroanTerbatas->value => 'PT',
            self::CommanditaireVennootschap->value => 'CV',
            self::Koperasi->value => 'Koperasi',
            self::Firma->value => 'Firma',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::PerseroanTerbatas => 'PT',
            self::CommanditaireVennootschap => 'CV',
            self::Koperasi => 'Koperasi',
            self::Firma => 'Firma',
        };
    }

}
