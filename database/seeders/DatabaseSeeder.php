<?php

namespace Database\Seeders;

use App\Models\BloodRequestPost;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            BloodTypeSeeder::class,
            RoleSeeder::class,
        ]);
        if (User::count() === 0) {
            User::factory(10)->create();
        }
        BloodRequestPost::factory(30)->create();
    }
}
