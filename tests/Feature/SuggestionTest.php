<?php 

use App\Models\User;
use App\Models\Suggestion;
use Laravel\Sanctum\Sanctum;

it('requires authentication to send suggestion', function () {
    $response = $this->postJson('/api/suggestions', [
        'youtube_link' => 'https://youtube.com/watch?v=teste'
    ]);

    $response->assertUnauthorized();
});

it('lets logged-in user send suggestion', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/suggestions', [
        'youtube_link' => 'https://youtube.com/watch?v=teste'
    ]);

    $response->assertCreated();
});
