<?php

declare(strict_types=1);

namespace App\Services\AI\Adapters;

use App\DTOs\RecipeAnalysisResult;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;

final class FakeRecipeAnalyzer implements RecipeAnalyzerInterface
{
    public function analyze(string $imageBase64, string $mimeType): RecipeAnalysisResult
    {
        return new RecipeAnalysisResult(
            foodName: 'Test Yemeği',
            ingredients: ['100g malzeme 1', '200ml malzeme 2'],
            steps: ['Malzemeleri hazırla.', 'Pişir ve servis et.'],
            cookingTime: '20 dakika',
            servings: 2,
            aiModel: 'fake',
            tokensUsed: 0,
        );
    }
}
