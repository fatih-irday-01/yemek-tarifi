<?php

declare(strict_types=1);

use App\Enums\AnalysisStatus;
use App\Jobs\AnalyzeRecipePhotoJob;
use App\Models\RecipeAnalysis;
use App\Models\User;
use App\Services\AI\Adapters\FakeRecipeAnalyzer;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function (): void {
    $this->app->bind(RecipeAnalyzerInterface::class, FakeRecipeAnalyzer::class);
    Storage::fake('public');
});

test('misafir kullanıcı fotoğraf yükleyemez', function (): void {
    $response = $this->post('/recipes/analyze', [
        'photo' => UploadedFile::fake()->image('food.jpg'),
    ]);

    $response->assertRedirect('/login');
});

test('giriş yapmış kullanıcı fotoğraf yükleyebilir ve job dispatch edilir', function (): void {
    Bus::fake();

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/recipes/analyze', [
        'photo' => UploadedFile::fake()->image('food.jpg', 800, 600)->size(500),
    ]);

    $response->assertRedirect();

    $analysis = RecipeAnalysis::first();
    expect($analysis)->not->toBeNull()
        ->and($analysis->user_id)->toBe($user->id)
        ->and($analysis->status)->toBe(AnalysisStatus::Pending);

    Bus::assertDispatched(AnalyzeRecipePhotoJob::class);
});

test('geçersiz dosya formatı reddedilir', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/recipes/analyze', [
        'photo' => UploadedFile::fake()->create('file.pdf', 100, 'application/pdf'),
    ]);

    $response->assertSessionHasErrors('photo');
});

test('8MB üzeri dosya reddedilir', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/recipes/analyze', [
        'photo' => UploadedFile::fake()->image('big.jpg')->size(9000),
    ]);

    $response->assertSessionHasErrors('photo');
});

test('kullanıcı kendi analizini görebilir', function (): void {
    $user = User::factory()->create();
    $analysis = RecipeAnalysis::factory()->for($user)->completed()->create();

    $response = $this->actingAs($user)->get("/recipes/{$analysis->id}");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('Recipe/Show')
        ->has('analysis')
    );
});

test('kullanıcı başkasının analizini göremez', function (): void {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $analysis = RecipeAnalysis::factory()->for($owner)->completed()->create();

    $response = $this->actingAs($other)->get("/recipes/{$analysis->id}");

    $response->assertNotFound();
});

test('status endpoint analiz durumunu döner', function (): void {
    $user = User::factory()->create();
    $analysis = RecipeAnalysis::factory()->for($user)->completed()->create();

    $response = $this->actingAs($user)->get("/api/analyses/{$analysis->id}/status");

    $response->assertOk()
        ->assertJsonPath('status', AnalysisStatus::Completed->value);
});

test('geçmiş listesi sadece kendi analizlerini gösterir', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();

    RecipeAnalysis::factory()->for($user)->count(3)->create();
    RecipeAnalysis::factory()->for($other)->count(2)->create();

    $response = $this->actingAs($user)->get('/history');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('Recipe/History')
        ->has('analyses.data', 3)
    );
});
