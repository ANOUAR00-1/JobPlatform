<?php

use App\Models\User;
use App\Models\Candidat;
use App\Models\Entreprise;
use App\Models\Candidature;
use App\Models\Ville;
use App\Models\Offre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it_has_entreprise_role', function () {
    // Arrange: Create a user with entreprise role
    $user = User::factory()->create([
        'email' => 'entreprise@example.com',
        'role' => 'entreprise',
    ]);

    // Assert: Check role
    expect($user->role)->toBe('entreprise');
});

test('it_has_candidat_role', function () {
    // Arrange: Create a user with candidat role
    $user = User::factory()->create([
        'email' => 'candidat@example.com',
        'role' => 'candidat',
    ]);

    // Assert: Check role
    expect($user->role)->toBe('candidat');
});

test('it_has_one_entreprise_relationship', function () {
    // Arrange: Create user with entreprise
    $user = User::factory()->create(['role' => 'entreprise']);
    
    $entreprise = Entreprise::create([
        'user_id' => $user->id,
        'raison_social' => 'Test Company',
        'adresse' => '123 Test St',
        'telephone' => '0612345678',
    ]);

    // Act: Load relationship
    $user->load('entreprise');

    // Assert: Check relationship
    expect($user->entreprise)->not->toBeNull();
    expect($user->entreprise->raison_social)->toBe('Test Company');
    expect($user->entreprise->id)->toBe($entreprise->id);
});

test('it_has_one_candidat_relationship', function () {
    // Arrange: Create ville first
    $ville = Ville::create(['nom' => 'Test City']);
    
    // Create user with candidat
    $user = User::factory()->create(['role' => 'candidat']);
    
    $candidat = Candidat::create([
        'user_id' => $user->id,
        'nom' => 'Doe',
        'prenom' => 'John',
        'telephone' => '0612345678',
        'email' => $user->email,
        'ville_id' => $ville->id,
    ]);

    // Act: Load relationship
    $user->load('candidat');

    // Assert: Check relationship
    expect($user->candidat)->not->toBeNull();
    expect($user->candidat->nom)->toBe('Doe');
    expect($user->candidat->id)->toBe($candidat->id);
});

test('it_has_many_candidatures_relationship_through_candidat', function () {
    // Arrange: Create ville and offres first
    $ville = Ville::create(['nom' => 'Test City']);
    
    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::create([
        'user_id' => $entrepriseUser->id,
        'raison_social' => 'Test Company',
        'adresse' => '123 Test St',
        'telephone' => '0512345678',
    ]);
    
    $offre1 = Offre::create([
        'entreprise_id' => $entreprise->id,
        'titre' => 'Test Job 1',
        'description' => 'Test Description',
        'type_contrat' => 'CDI',
        'ville_id' => $ville->id,
        'date_expiration' => now()->addDays(30),
    ]);
    
    $offre2 = Offre::create([
        'entreprise_id' => $entreprise->id,
        'titre' => 'Test Job 2',
        'description' => 'Test Description',
        'type_contrat' => 'CDD',
        'ville_id' => $ville->id,
        'date_expiration' => now()->addDays(30),
    ]);
    
    // Create user and candidat
    $user = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::create([
        'user_id' => $user->id,
        'nom' => 'Test',
        'prenom' => 'User',
        'telephone' => '0612345678',
        'email' => $user->email,
        'ville_id' => $ville->id,
    ]);
    
    $candidature1 = Candidature::create([
        'candidat_id' => $candidat->id,
        'offre_id' => $offre1->id,
        'statut' => 'en_attente',
        'cv_path' => 'cv1.pdf',
    ]);

    $candidature2 = Candidature::create([
        'candidat_id' => $candidat->id,
        'offre_id' => $offre2->id,  // Different job offer
        'statut' => 'acceptee',
        'cv_path' => 'cv2.pdf',
    ]);

    // Act: Load candidat and its candidatures
    $user->load('candidat.candidatures');

    // Assert: Check relationship through candidat
    expect($user->candidat->candidatures)->toHaveCount(2);
    expect($user->candidat->candidatures->first()->statut)->toBe('en_attente');
});

test('it_hashes_password_automatically', function () {
    // Arrange & Act: Create user with plain password
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => 'plainpassword',
    ]);

    // Assert: Password should be hashed
    expect($user->password)->not->toBe('plainpassword');
    expect(strlen($user->password))->toBeGreaterThan(20); // Hashed passwords are long
    expect(\Illuminate\Support\Facades\Hash::check('plainpassword', $user->password))->toBeTrue();
});

test('it_hides_password_from_json_serialization', function () {
    // Arrange: Create user
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => 'secret123',
    ]);

    // Act: Convert to JSON
    $json = $user->toArray();

    // Assert: Password should not be in array
    expect($json)->not->toHaveKey('password');
    expect($json)->toHaveKey('email');
});

test('it_hides_remember_token_from_json_serialization', function () {
    // Arrange: Create user with remember token
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'remember_token' => 'some-token-value',
    ]);

    // Act: Convert to JSON
    $json = $user->toArray();

    // Assert: Remember token should not be in array
    expect($json)->not->toHaveKey('remember_token');
});
