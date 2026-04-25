<?php

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Ville;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed villes for tests
    Ville::insert([
        ['nom' => 'Casablanca', 'created_at' => now(), 'updated_at' => now()],
        ['nom' => 'Rabat', 'created_at' => now(), 'updated_at' => now()],
        ['nom' => 'Marrakech', 'created_at' => now(), 'updated_at' => now()],
    ]);
});

test('can list all public job offers', function () {
    $ville = Ville::first();
    $user = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $user->id]);
    
    for ($i = 0; $i < 5; $i++) {
        Offre::create([
            'entreprise_id' => $entreprise->id,
            'ville_id' => $ville->id,
            'titre' => "Développeur PHP $i",
            'description' => 'Description du poste',
            'competences_requises' => ['PHP', 'Laravel'],
            'salaire' => '8000-12000',
            'type_contrat' => 'CDI',
            'date_expiration' => now()->addDays(30),
            'statut' => 'ouverte',
        ]);
    }

    $response = $this->getJson('/api/jobs');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'titre', 'description', 'statut']
            ]
        ]);
});

test('can view single job offer', function () {
    $ville = Ville::first();
    $user = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $user->id]);
    
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description du poste',
        'competences_requises' => ['PHP', 'Laravel'],
        'salaire' => '8000-12000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $response = $this->getJson("/api/jobs/{$offre->id}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $offre->id,
                'titre' => $offre->titre,
            ]
        ]);
});

test('entreprise can create job offer', function () {
    $ville = Ville::first();
    $user = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/offres', [
            'titre' => 'Développeur PHP',
            'description' => 'Nous recherchons un développeur PHP expérimenté',
            'competences_requises' => ['PHP', 'Laravel', 'MySQL'],
            'ville_id' => $ville->id,
            'salaire' => '8000-12000',
            'type_contrat' => 'CDI',
            'date_expiration' => now()->addDays(30)->format('Y-m-d'),
        ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Offre d\'emploi créée avec succès.',
        ]);

    $this->assertDatabaseHas('offres', [
        'titre' => 'Développeur PHP',
        'entreprise_id' => $entreprise->id,
    ]);
});

test('candidat cannot create job offer', function () {
    $ville = Ville::first();
    $user = User::factory()->create(['role' => 'candidat']);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/offres', [
            'titre' => 'Développeur PHP',
            'description' => 'Test',
            'competences_requises' => 'PHP',
            'ville_id' => $ville->id,
            'salaire' => '8000',
            'type_contrat' => 'CDI',
            'date_expiration' => now()->addDays(30)->format('Y-m-d'),
        ]);

    $response->assertStatus(403);
});

test('can filter jobs by contract type', function () {
    $ville = Ville::first();
    $user = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $user->id]);
    
    Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur CDI',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'type_contrat' => 'CDI',
        'salaire' => '8000',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);
    
    Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur CDD',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'type_contrat' => 'CDD',
        'salaire' => '7000',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $response = $this->getJson('/api/jobs?contract_types=CDI');

    $response->assertStatus(200);
    $data = $response->json('data');
    
    foreach ($data as $job) {
        expect($job['type_contrat'])->toBe('CDI');
    }
});

test('can search jobs by title', function () {
    $ville = Ville::first();
    $user = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $user->id]);
    
    Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP Senior',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '10000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);
    
    Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Designer UI/UX',
        'description' => 'Description',
        'competences_requises' => ['Figma'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $response = $this->getJson('/api/jobs?search=PHP');

    $response->assertStatus(200);
    $data = $response->json('data');
    
    expect(count($data))->toBeGreaterThan(0);
    expect($data[0]['titre'])->toContain('PHP');
});
