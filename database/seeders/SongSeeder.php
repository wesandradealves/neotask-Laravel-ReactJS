<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;
use Illuminate\Support\Facades\DB;

class SongSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Song::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Song::factory()->count(20)->create();
    }
}
