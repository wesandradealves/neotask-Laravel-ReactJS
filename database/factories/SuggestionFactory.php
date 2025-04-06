<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Suggestion;
use App\Models\User;
use Illuminate\Support\Carbon;

class SuggestionFactory extends Factory
{
    protected $model = Suggestion::class;

    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-2 months', 'now');

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'youtube_link' => 'https://www.youtube.com/watch?v=' . $this->faker->unique()->regexify('[A-Za-z0-9_-]{11}'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'created_at' => $createdAt,
            'updated_at' => Carbon::parse($createdAt)->addDays(rand(0, 15)),
        ];
    }
}
