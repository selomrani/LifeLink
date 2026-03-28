<?php

namespace Database\Seeders;

use App\Models\BloodType;
use Illuminate\Database\Seeder;

class BloodTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'O-', 'give' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'], 'receive' => ['O-']],
            ['name' => 'O+', 'give' => ['A+', 'B+', 'AB+', 'O+'], 'receive' => ['O+', 'O-']],
            ['name' => 'A-', 'give' => ['A+', 'A-', 'AB+', 'AB-'], 'receive' => ['A-', 'O-']],
            ['name' => 'A+', 'give' => ['A+', 'AB+'], 'receive' => ['A+', 'A-', 'O+', 'O-']],
            ['name' => 'B-', 'give' => ['B+', 'B-', 'AB+', 'AB-'], 'receive' => ['B-', 'O-']],
            ['name' => 'B+', 'give' => ['B+', 'AB+'], 'receive' => ['B+', 'B-', 'O+', 'O-']],
            ['name' => 'AB-', 'give' => ['AB+', 'AB-'], 'receive' => ['AB-', 'A-', 'B-', 'O-']],
            ['name' => 'AB+', 'give' => ['AB+'], 'receive' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']],
        ];

        foreach ($data as $type) {
            BloodType::create([
                'name' => $type['name'],
                'can_give_to' => $type['give'],
                'can_receive_from' => $type['receive'],
            ]);
        }
    }
}
