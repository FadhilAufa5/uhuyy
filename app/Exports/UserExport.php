<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::query()->with(['branch', 'roles', 'permissions'])
            ->get()
            ->map(function ($user) {
                // Attach roles and permissions as strings
                $user->user_roles = $user->roles->pluck('name')->implode(', ');
                $user->user_permissions = $user->getAllPermissions()->pluck('name')->implode(', ');

                return $user;
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Username',
            'Email',
            'Status',
            'Status Verifikasi',
            'Role',
            'Permissions',
            'Branch Code',
            'Branch Name',
            'Tgl Dibuat',
            'Tgl Diupdate',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->username,
            $user->email,
            $user->is_active ? 'active' : 'blocked',
            $user->email_verified_at ? 'verified' : 'not verified',
            $user->user_roles,
            $user->user_permissions,
            $user->branch->code ?? '',
            $user->branch->name ?? '',
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
