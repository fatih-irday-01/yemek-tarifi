<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 *
 */
final readonly class RecipeAnalysisResult
{
    /**
     * @param string $foodName
     * @param array $ingredients
     * @param array $steps
     * @param string $cookingTime
     * @param int $servings
     * @param string $aiModel
     * @param int $tokensUsed
     */
    public function __construct(
        public string $foodName,
        public array $ingredients,
        public array $steps,
        public string $cookingTime,
        public int $servings,
        public string $aiModel,
        public int $tokensUsed,
    ) {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'food_name' => $this->foodName,
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'cooking_time' => $this->cookingTime,
            'servings' => $this->servings,
        ];
    }
}
