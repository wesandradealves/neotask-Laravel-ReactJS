<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Suggestion;
use App\Models\User;

class SuggestionSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            \Log::warning('Nenhum usuário encontrado para associar às sugestões.');
            return;
        }

        Suggestion::factory()->count(10)->create();
    }
}
