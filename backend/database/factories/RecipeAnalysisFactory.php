<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnalysisStatus;
use App\Models\RecipeAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecipeAnalysis>
 */
final class RecipeAnalysisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'photo_path' => 'recipes/1/test-photo.jpg',
            'photo_disk' => 'public',
            'status' => AnalysisStatus::Pending,
            'food_name' => null,
            'recipe' => null,
            'error_message' => null,
            'ai_model' => null,
            'tokens_used' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'status' => AnalysisStatus::Completed,
            'food_name' => 'Mercimek Çorbası',
            'recipe' => [
                'ingredients' => ['200g kırmızı mercimek', '1 soğan', '2 yemek kaşığı tereyağı'],
                'steps' => ['Mercimeği yıka.', 'Soğanı kavur.', 'Pişir ve servis et.'],
                'cooking_time' => '30 dakika',
                'servings' => 4,
            ],
            'ai_model' => 'claude-sonnet-4-6',
            'tokens_used' => 512,
        ]);
    }

    public function failed(): static
    {
        return $this->state([
            'status' => AnalysisStatus::Failed,
            'error_message' => 'AI servisi yanıt vermedi.',
        ]);
    }
}
