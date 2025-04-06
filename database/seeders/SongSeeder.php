<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        Song::insert([
            [
                'title' => 'Pagode em Brasília',
                'youtube_link' => 'https://www.youtube.com/watch?v=VIDEO1',
                'plays' => 123,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cochilou o Cacimbo Cai',
                'youtube_link' => 'https://www.youtube.com/watch?v=VIDEO2',
                'plays' => 98,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Moda da Mula Preta',
                'youtube_link' => 'https://www.youtube.com/watch?v=VIDEO3',
                'plays' => 245,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
