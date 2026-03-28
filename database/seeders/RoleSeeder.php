<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'System administrator with moderation powers.',
            ],
            [
                'name' => 'donor',
                'description' => 'User who provides blood donations.',
            ],
            [
                'name' => 'recipient',
                'description' => 'User who requests blood donations.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
