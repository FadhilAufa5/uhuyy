<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionsSeeder::class,
            ]);


        User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'super',
            'email' => 'super@admin.com',
            // 'branch_id' => 1,
        ])->assignRole(Roles::SuperAdmin->value);

        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $user->assignRole(Roles::User->value);
        }
    //     // User::factory(10)->unverified()->create();
    }
}
