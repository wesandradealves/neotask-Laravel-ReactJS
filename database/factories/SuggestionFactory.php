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
        $youtubeLink = 'https://www.youtube.com/watch?v=' . $this->faker->unique()->regexify('[A-Za-z0-9_-]{11}');

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'youtube_link' => $youtubeLink,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'created_at' => $createdAt,
            'updated_at' => Carbon::parse($createdAt)->addDays(rand(0, 15)),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Suggestion $suggestion) {
            $suggestion->video_id = $this->extractVideoId($suggestion->youtube_link);
        })->afterCreating(function (Suggestion $suggestion) {
            $suggestion->video_id = $this->extractVideoId($suggestion->youtube_link);
            $suggestion->save();
        });
    }

    private function extractVideoId(string $url)
    {
        preg_match('%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches);
        return $matches[1] ?? null;
    }
}
