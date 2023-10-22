<?php

namespace Database\Factories;

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
            'book_id' => null,
            'review' => fake()->paragraph,
            'rating' => fake()->numberBetween(1, 5),
            'created_at' => fake()->dateTimeBetween('-3 years', 'now', null),
            'updated_at' => function (array $attrs) {
                return fake()->dateTimeBetween($attrs['created_at'], 'now', null);
            }
        ];
    }

    public function goodRating()
    {
        return $this->state(function (array $att) {
            return [
                'rating' => fake()->numberBetween(4, 5),
            ];
        });
    }

    public function averageRating()
    {
        return $this->state(function (array $att) {
            return [
                'rating' => fake()->numberBetween(3, 4),
            ];
        });
    }

    public function badRating()
    {
        return $this->state(function (array $att) {
            return [
                'rating' => fake()->numberBetween(1, 3),
            ];
        });
    }
}
