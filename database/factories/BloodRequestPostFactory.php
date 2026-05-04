<?php

namespace Database\Factories;

use App\Models\BloodType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BloodRequestPostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'blood_type_id' => BloodType::pluck('id')->random(),
            'description' => $this->faker->paragraph(2),
            'location' => $this->faker->randomElement(['Casablanca', 'Rabat', 'Youssoufia', 'Marrakech', 'Fès']),
            'needed_by' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'user_id' => User::exists() ? User::pluck('id')->random() : User::factory(),
            'status' => $this->faker->randomElement(['open', 'urgent', 'completed']),
            'media_path' => null,
        ];
    }
}
