<?php

declare(strict_types=1);

use App\Ai\Agents\RecipeAnalyzerAgent;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Attributes\Model;

test('groq api erişilebilir ve konfigüre edilen model aktif', function (): void {
    $apiKey = config('ai.providers.groq.key');

    if (! $apiKey) {
        $this->markTestSkipped('GROQ_API_KEY tanımlı değil — bu test atlandı.');
    }

    $attrs = (new ReflectionClass(RecipeAnalyzerAgent::class))->getAttributes(Model::class);

    expect($attrs)->not->toBeEmpty('RecipeAnalyzerAgent üzerinde #[Model] attribute bulunamadı.');

    $configuredModel = $attrs[0]->newInstance()->value;

    $groqModelsEndpoint = 'https://api.groq.com/openai/v1/models';

    $response = Http::withToken($apiKey)
        ->timeout(10)
        ->get($groqModelsEndpoint);

    expect($response->successful())
        ->toBeTrue('Groq API erişilemiyor — key geçersiz veya servis kapalı.');

    $activeModelIds = collect($response->json('data'))->pluck('id');

    expect($activeModelIds->contains($configuredModel))
        ->toBeTrue("Model '{$configuredModel}' Groq'ta mevcut değil veya kaldırılmış.");
})->group('integration');
