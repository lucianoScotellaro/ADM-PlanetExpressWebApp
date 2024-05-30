<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reviewer_id' => User::factory()->create()->id,
            'reviewed_id' => User::factory()->create()->id,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->text()
        ];
    }
}
