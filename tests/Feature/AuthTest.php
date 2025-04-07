<?php 

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can register a new user', function () {
    $response = $this->postJson('/register', [
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'password' => 'senha123',
        'password_confirmation' => 'senha123',
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('users', ['email' => 'joao@example.com']);
});

it('can login with valid credentials', function () {
    User::factory()->create([
        'email' => 'maria@example.com',
        'password' => bcrypt('senha123')
    ]);

    $response = $this->postJson('/login', [
        'email' => 'maria@example.com',
        'password' => 'senha123',
    ]);

    $response->assertOk()->assertJsonStructure(['token']);
});

it('cannot login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'jose@example.com',
        'password' => bcrypt('senha123')
    ]);

    $response = $this->postJson('/login', [
        'email' => 'jose@example.com',
        'password' => 'senhaErrada',
    ]);

    $response->assertUnauthorized();
});

it('can logout the authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/logout');

    $response->assertOk();
});

it('blocks access to protected route without token', function () {
    $response = $this->getJson('/api/user');
    $response->assertUnauthorized();
});

it('allows access to protected route with token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/user');

    $response->assertOk()->assertJsonFragment([
        'email' => $user->email,
    ]);
});
