<?php

declare(strict_types=1);

namespace App\Services\AI\Adapters;

use App\DTOs\RecipeAnalysisResult;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class ClaudeRecipeAnalyzer implements RecipeAnalyzerInterface
{
    private const MODEL = 'claude-sonnet-4-6';
    private const API_URL = 'https://api.anthropic.com/v1/messages';

    public function __construct(
        private readonly string $apiKey,
    ) {}

    public function analyze(string $imageBase64, string $mimeType): RecipeAnalysisResult
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post(self::API_URL, [
            'model' => self::MODEL,
            'max_tokens' => 1024,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'image',
                            'source' => [
                                'type' => 'base64',
                                'media_type' => $mimeType,
                                'data' => $imageBase64,
                            ],
                        ],
                        [
                            'type' => 'text',
                            'text' => $this->buildPrompt(),
                        ],
                    ],
                ],
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Claude API hatası: '.$response->body());
        }

        $content = $response->json('content.0.text');

        return $this->parseResponse($content, $response->json('usage.input_tokens', 0) + $response->json('usage.output_tokens', 0));
    }

    private function buildPrompt(): string
    {
        return <<<'PROMPT'
Bu fotoğraftaki yemeği tanımla ve Türkçe tarif çıkar.

Aşağıdaki JSON formatında yanıt ver, başka hiçbir şey yazma:
{
  "food_name": "Yemeğin adı",
  "ingredients": ["malzeme 1 - miktar", "malzeme 2 - miktar"],
  "steps": ["adım 1", "adım 2"],
  "cooking_time": "30 dakika",
  "servings": 4
}
PROMPT;
    }

    private function parseResponse(string $content, int $tokensUsed): RecipeAnalysisResult
    {
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');

        if ($jsonStart === false || $jsonEnd === false) {
            throw new RuntimeException('AI yanıtı geçerli JSON içermiyor.');
        }

        $json = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('AI yanıtı parse edilemedi: '.json_last_error_msg());
        }

        return new RecipeAnalysisResult(
            foodName: $data['food_name'] ?? 'Bilinmeyen Yemek',
            ingredients: $data['ingredients'] ?? [],
            steps: $data['steps'] ?? [],
            cookingTime: $data['cooking_time'] ?? '',
            servings: (int) ($data['servings'] ?? 2),
            aiModel: self::MODEL,
            tokensUsed: $tokensUsed,
        );
    }
}
