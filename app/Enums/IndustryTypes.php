<?php

namespace App\Enums;

enum IndustryTypes: string
{
    case ToolsMachineAndSpareParts = 'tools-machine';
    case RawMaterial = 'raw-material';
    case Packaging = 'packaging';
    case ConsumerProduct = 'consumer-product';
    case Services = 'services';
    case Media = 'media';
    case EngineeringAndBuilding = 'engineering-and-building';
    case InformationTechnology = 'information-technology';
    case Vehicles = 'vehicles';
    case Stationery = 'stationery';

    public static function labels(): array
    {
        return [
            self::ToolsMachineAndSpareParts->value => 'Alat, Mesin dan Spare Parts',
            self::RawMaterial->value => 'Bahan Baku',
            self::Packaging->value =>  'Bahan Kemasan',
            self::ConsumerProduct->value => 'Produk Jadi',
            self::Services->value => 'Jasa',
            self::Media->value => 'Media Massa dan Elektronik',
            self::EngineeringAndBuilding->value => 'Teknik dan Bangunan',
            self::InformationTechnology->value => 'Teknologi Informasi',
            self::Vehicles->value => 'Kendaraan',
            self::Stationery->value => 'Alat Tulis Kantor',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::ToolsMachineAndSpareParts => 'Alat, Mesin dan Spare Parts',
            self::RawMaterial => 'Bahan Baku',
            self::Packaging =>  'Bahan Kemasan',
            self::ConsumerProduct => 'Produk Jadi',
            self::Services => 'Jasa',
            self::Media => 'Media Massa dan Elektronik',
            self::EngineeringAndBuilding => 'Teknik dan Bangunan',
            self::InformationTechnology => 'Teknologi Informasi',
            self::Vehicles => 'Kendaraan',
            self::Stationery => 'ATK',
        };
    }

    public static function options(): array
    {
        return array_map(fn ($indType) => [
            'value' => $indType->value,
            'label' => $indType->label()
        ], self::cases());
    }

}
