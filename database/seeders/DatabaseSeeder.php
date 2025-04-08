<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Database\Seeders\SongSeeder;
use Database\Seeders\SuggestionSeeder;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SongSeeder::class,
        ]);

        $this->call([
            SuggestionSeeder::class,
        ]);

        $this->call([
            AdminUserSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ]
        );
    }
}
