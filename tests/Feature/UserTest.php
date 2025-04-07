<?php 

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns user details when authenticated', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/user');
    $response->assertOk()->assertJsonFragment(['email' => $user->email]);
});
