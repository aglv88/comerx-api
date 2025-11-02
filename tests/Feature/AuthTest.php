<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'username' => 'testuser',
        'password' => bcrypt('password123'),
    ]);
});

test('user can login with valid credentials', function () {
    $response = $this->postJson('/api/auth/login', [
        'username' => 'testuser',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);

    expect($response->json('success'))->toBeTrue();
    expect($response->json('data.token_type'))->toBe('bearer');
});

test('user cannot login with invalid username', function () {
    $response = $this->postJson('/api/auth/login', [
        'username' => 'wronguser',
        'password' => 'password123',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Invalid credentials',
        ]);
});

test('user cannot login with invalid password', function () {
    $response = $this->postJson('/api/auth/login', [
        'username' => 'testuser',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'Invalid credentials',
        ]);
});

test('login requires username', function () {
    $response = $this->postJson('/api/auth/login', [
        'password' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['username']);
});

test('login requires password', function () {
    $response = $this->postJson('/api/auth/login', [
        'username' => 'testuser',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('login requires password with minimum length', function () {
    $response = $this->postJson('/api/auth/login', [
        'username' => 'testuser',
        'password' => '12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('authenticated user can logout', function () {
    $token = auth('api')->login($this->user);

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/auth/logout');

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
});

test('unauthenticated user cannot logout', function () {
    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(401);
});

test('authenticated user can get their profile', function () {
    $token = auth('api')->login($this->user);

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/auth/me');

    $response->assertSuccessful()
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $this->user->id,
                'username' => 'testuser',
            ],
        ]);
});

test('unauthenticated user cannot get profile', function () {
    $response = $this->getJson('/api/auth/me');

    $response->assertStatus(401);
});

test('authenticated user can refresh token', function () {
    $token = auth('api')->login($this->user);

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/auth/refresh');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);

    expect($response->json('success'))->toBeTrue();
    expect($response->json('data.token_type'))->toBe('bearer');
    expect($response->json('data.access_token'))->not->toBe($token);
});

test('unauthenticated user cannot refresh token', function () {
    $response = $this->postJson('/api/auth/refresh');

    $response->assertStatus(401);
});
