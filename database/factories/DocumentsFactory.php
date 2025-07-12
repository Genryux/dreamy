<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documents>
 */
class DocumentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'file_type_restriction' => $this->faker->randomElement(['jpg', 'png','pdf']),
            'max_file_size' => $this->faker->numberBetween(100, 5000), // in KB
        ];
    }
}
