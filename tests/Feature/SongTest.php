<?php 

use App\Models\User;
use App\Models\Song;
use Laravel\Sanctum\Sanctum;

it('can list songs', function () {
    Song::factory()->count(3)->create();
    $response = $this->getJson('/api/songs');
    $response->assertOk()->assertJsonCount(3, 'data');
});

it('requires authentication to create song', function () {
    $response = $this->postJson('/api/songs', [
        'title' => 'Modão',
        'youtube_link' => 'https://youtube.com/watch?v=123'
    ]);
    $response->assertUnauthorized();
});

it('allows admin to create a song', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Sanctum::actingAs($admin);

    $response = $this->postJson('/api/songs', [
        'title' => 'Pagode em Brasília',
        'youtube_link' => 'https://youtube.com/watch?v=abc123'
    ]);

    $response->assertCreated();
});
