<?php

declare(strict_types=1);

namespace App\Services\AI\Adapters;

use App\Ai\Agents\RecipeAnalyzerAgent;
use App\DTOs\RecipeAnalysisResult;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;
use Laravel\Ai\Files\Image;
use RuntimeException;

/**
 * Class GroqRecipeAnalyzer
 */
final class GroqRecipeAnalyzer implements RecipeAnalyzerInterface
{
    private const MODEL = 'meta-llama/llama-4-scout-17b-16e-instruct';

    /**
     * @param  string  $disk  Storage disk adı
     * @param  string  $path  Disk üzerindeki dosya yolu
     */
    public function analyze(string $disk, string $path): RecipeAnalysisResult
    {
        $response = (new RecipeAnalyzerAgent)->prompt(
            'Fotoğraftaki yemeği tanımla ve Türkçe tarif çıkar.',
            attachments: [Image::fromStorage($path, $disk)],
        );

        if (! isset($response['food_name'])) {
            throw new RuntimeException('AI yanıtı beklenen formatta değil.');
        }

        return new RecipeAnalysisResult(
            foodName: (string) $response['food_name'],
            ingredients: (array) $response['ingredients'],
            steps: (array) $response['steps'],
            cookingTime: (string) $response['cooking_time'],
            servings: (int) $response['servings'],
            aiModel: self::MODEL,
            tokensUsed: 0,
        );
    }
}
