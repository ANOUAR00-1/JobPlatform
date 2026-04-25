<?php

use App\Models\User;
use App\Models\Candidat;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Candidature;
use App\Models\Ville;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    
    // Seed villes for tests
    Ville::insert([
        ['nom' => 'Casablanca', 'created_at' => now(), 'updated_at' => now()],
        ['nom' => 'Rabat', 'created_at' => now(), 'updated_at' => now()],
        ['nom' => 'Marrakech', 'created_at' => now(), 'updated_at' => now()],
    ]);
});

test('candidat can apply for job with CV', function () {
    $ville = Ville::first();
    
    // Create candidat
    $user = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::factory()->create([
        'user_id' => $user->id,
        'ville_id' => $ville->id,
    ]);

    // Create job offer
    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $entrepriseUser->id]);
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    // Create a real PDF file with proper magic bytes for testing
    $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/Resources <<\n/Font <<\n/F1 <<\n/Type /Font\n/Subtype /Type1\n/BaseFont /Helvetica\n>>\n>>\n>>\n/MediaBox [0 0 612 792]\n/Contents 4 0 R\n>>\nendobj\n4 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 12 Tf\n100 700 Td\n(Test CV) Tj\nET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\n0000000317 00000 n\ntrailer\n<<\n/Size 5\n/Root 1 0 R\n>>\nstartxref\n408\n%%EOF";
    $cv = UploadedFile::fake()->createWithContent('cv.pdf', $pdfContent);

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/candidatures', [
            'offre_id' => $offre->id,
            'cv' => $cv,
            'lettre_motivation' => 'Je suis très motivé pour ce poste.',
        ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Candidature soumise avec succès.',
        ]);

    $this->assertDatabaseHas('candidatures', [
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'statut' => 'en_attente',
    ]);

    Storage::disk('public')->assertExists('cvs/' . $cv->hashName());
});

test('candidat cannot apply twice for same job', function () {
    $ville = Ville::first();
    
    $user = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::factory()->create([
        'user_id' => $user->id,
        'ville_id' => $ville->id,
    ]);

    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $entrepriseUser->id]);
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    // First application
    Candidature::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'cv_path' => 'cvs/test.pdf',
        'statut' => 'en_attente',
    ]);

    // Create a real PDF file with proper magic bytes for testing
    $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/Resources <<\n/Font <<\n/F1 <<\n/Type /Font\n/Subtype /Type1\n/BaseFont /Helvetica\n>>\n>>\n>>\n/MediaBox [0 0 612 792]\n/Contents 4 0 R\n>>\nendobj\n4 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 12 Tf\n100 700 Td\n(Test CV) Tj\nET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\n0000000317 00000 n\ntrailer\n<<\n/Size 5\n/Root 1 0 R\n>>\nstartxref\n408\n%%EOF";
    $cv = UploadedFile::fake()->createWithContent('cv.pdf', $pdfContent);

    // Second application attempt
    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/candidatures', [
            'offre_id' => $offre->id,
            'cv' => $cv,
        ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Vous avez déjà postulé à cette offre.',
        ]);
});

test('entreprise can accept candidature', function () {
    $ville = Ville::first();
    
    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $entrepriseUser->id]);
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $candidatUser = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::factory()->create([
        'user_id' => $candidatUser->id,
        'ville_id' => $ville->id,
    ]);

    $candidature = Candidature::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'cv_path' => 'cvs/test.pdf',
        'statut' => 'en_attente',
    ]);

    $response = $this->actingAs($entrepriseUser, 'sanctum')
        ->postJson("/api/candidatures/{$candidature->id}/accepter");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Candidature acceptée.',
        ]);

    $this->assertDatabaseHas('candidatures', [
        'id' => $candidature->id,
        'statut' => 'acceptee',
    ]);
});

test('entreprise can reject candidature', function () {
    $ville = Ville::first();
    
    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $entrepriseUser->id]);
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $candidatUser = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::factory()->create([
        'user_id' => $candidatUser->id,
        'ville_id' => $ville->id,
    ]);

    $candidature = Candidature::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'cv_path' => 'cvs/test.pdf',
        'statut' => 'en_attente',
    ]);

    $response = $this->actingAs($entrepriseUser, 'sanctum')
        ->postJson("/api/candidatures/{$candidature->id}/refuser");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Candidature refusée.',
        ]);

    $this->assertDatabaseHas('candidatures', [
        'id' => $candidature->id,
        'statut' => 'refusee',
    ]);
});

test('entreprise can evaluate candidature', function () {
    $ville = Ville::first();
    
    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $entrepriseUser->id]);
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $candidatUser = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::factory()->create([
        'user_id' => $candidatUser->id,
        'ville_id' => $ville->id,
    ]);

    $candidature = Candidature::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'cv_path' => 'cvs/test.pdf',
        'statut' => 'en_attente',
    ]);

    $response = $this->actingAs($entrepriseUser, 'sanctum')
        ->putJson("/api/candidatures/{$candidature->id}/evaluate", [
            'note_evaluation' => 4,
            'commentaire_recruteur' => 'Excellent candidat',
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Candidature évaluée avec succès.',
        ]);

    $this->assertDatabaseHas('candidatures', [
        'id' => $candidature->id,
        'note_evaluation' => 4,
        'commentaire_recruteur' => 'Excellent candidat',
    ]);
});

test('candidat cannot accept own candidature', function () {
    $ville = Ville::first();
    
    $candidatUser = User::factory()->create(['role' => 'candidat']);
    $candidat = Candidat::factory()->create([
        'user_id' => $candidatUser->id,
        'ville_id' => $ville->id,
    ]);

    $entrepriseUser = User::factory()->create(['role' => 'entreprise']);
    $entreprise = Entreprise::factory()->create(['user_id' => $entrepriseUser->id]);
    $offre = Offre::create([
        'entreprise_id' => $entreprise->id,
        'ville_id' => $ville->id,
        'titre' => 'Développeur PHP',
        'description' => 'Description',
        'competences_requises' => ['PHP'],
        'salaire' => '8000',
        'type_contrat' => 'CDI',
        'date_expiration' => now()->addDays(30),
        'statut' => 'ouverte',
    ]);

    $candidature = Candidature::create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'cv_path' => 'cvs/test.pdf',
        'statut' => 'en_attente',
    ]);

    $response = $this->actingAs($candidatUser, 'sanctum')
        ->postJson("/api/candidatures/{$candidature->id}/accepter");

    $response->assertStatus(403);
});
