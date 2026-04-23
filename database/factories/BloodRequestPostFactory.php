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
            'blood_type' => BloodType::pluck('name')->random(),
            'description' => $this->faker->paragraph(2),
            'location' => $this->faker->randomElement(['Casablanca', 'Rabat', 'Youssoufia', 'Marrakech', 'Fès']),
            'needed_by' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            // On lie le post à un utilisateur existant
            'user_id' => User::exists() ? User::pluck('id')->random() : User::factory(),
            'status' => $this->faker->randomElement(['pending', 'urgent', 'completed']),
            'media_path' => null,
        ];
    }
}
