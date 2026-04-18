<?php

use App\Models\User;
use App\Models\Candidat;
use App\Models\Ville;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Mock Cloudflare Turnstile API to always return success
    Http::fake([
        'challenges.cloudflare.com/*' => Http::response([
            'success' => true,
        ], 200),
    ]);
    
    // Create a ville for candidat tests
    $this->ville = Ville::create([
        'nom' => 'Casablanca',
    ]);
});

test('it_returns_token_when_login_with_valid_credentials', function () {
    // Arrange: Create a candidat user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'role' => 'candidat',
    ]);

    Candidat::create([
        'user_id' => $user->id,
        'nom' => 'Doe',
        'prenom' => 'John',
        'telephone' => '0612345678',
        'email' => 'test@example.com',
        'ville_id' => $this->ville->id,
    ]);

    // Act: Attempt login
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
        'cf-turnstile-response' => 'test-token',
    ]);

    // Assert: Check response
    $response->assertStatus(200)
        ->assertJsonStructure([
            'user' => ['id', 'email', 'role'],
            'profile',
            'access_token',
        ]);

    expect($response->json('access_token'))->not->toBeNull();
    expect($response->json('user.email'))->toBe('test@example.com');
});

test('it_returns_401_when_login_with_invalid_credentials', function () {
    // Arrange: Create a user
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'role' => 'candidat',
    ]);

    // Act: Attempt login with wrong password
    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
        'cf-turnstile-response' => 'test-token',
    ]);

    // Assert: Check unauthorized response
    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Les identifiants sont incorrects.',
        ]);
});

test('it_returns_401_when_login_with_non_existent_email', function () {
    // Act: Attempt login with non-existent email
    $response = $this->postJson('/api/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password123',
        'cf-turnstile-response' => 'test-token',
    ]);

    // Assert: Check unauthorized response
    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Les identifiants sont incorrects.',
        ]);
});

test('it_creates_candidat_account_on_registration', function () {
    // Act: Register a new candidat
    $response = $this->postJson('/api/register', [
        'email' => 'newcandidat@example.com',
        'password' => 'password123',
        'nom' => 'Smith',
        'prenom' => 'Jane',
        'telephone' => '0698765432',
        'ville_id' => $this->ville->id,
        'cf-turnstile-response' => 'test-token',
    ]);

    // Assert: Check response
    $response->assertStatus(200)
        ->assertJson([
            'requires_verification' => true,
            'email' => 'newcandidat@example.com',
        ]);

    // Verify user was created
    $this->assertDatabaseHas('users', [
        'email' => 'newcandidat@example.com',
        'role' => 'candidat',
    ]);

    // Verify candidat profile was created
    $this->assertDatabaseHas('candidats', [
        'email' => 'newcandidat@example.com',
        'nom' => 'Smith',
        'prenom' => 'Jane',
    ]);
});

test('it_returns_422_when_registering_with_duplicate_email', function () {
    // Arrange: Create existing user
    User::factory()->create([
        'email' => 'existing@example.com',
        'role' => 'candidat',
    ]);

    // Act: Try to register with same email
    $response = $this->postJson('/api/register', [
        'email' => 'existing@example.com',
        'password' => 'password123',
        'nom' => 'Smith',
        'prenom' => 'Jane',
        'telephone' => '0698765432',
        'ville_id' => $this->ville->id,
        'cf-turnstile-response' => 'test-token',
    ]);

    // Assert: Check validation error
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('it_verifies_email_and_returns_token', function () {
    // Arrange: Create unverified user
    $user = User::factory()->create([
        'email' => 'verify@example.com',
        'role' => 'candidat',
        'is_verified' => false,
        'verification_code' => '123456',
    ]);

    Candidat::create([
        'user_id' => $user->id,
        'nom' => 'Test',
        'prenom' => 'User',
        'telephone' => '0612345678',
        'email' => 'verify@example.com',
        'ville_id' => $this->ville->id,
    ]);

    // Act: Verify email
    $response = $this->postJson('/api/verify-email', [
        'email' => 'verify@example.com',
        'code' => '123456',
    ]);

    // Assert: Check response
    $response->assertStatus(200)
        ->assertJsonStructure([
            'user',
            'profile',
            'access_token',
        ]);

    // Verify user is now verified
    $this->assertDatabaseHas('users', [
        'email' => 'verify@example.com',
        'is_verified' => true,
        'verification_code' => null,
    ]);
});
