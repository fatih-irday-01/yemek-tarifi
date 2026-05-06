<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class RecipeAnalysisResult
{
    public function __construct(
        public string $foodName,
        public array $ingredients,
        public array $steps,
        public string $cookingTime,
        public int $servings,
        public string $aiModel,
        public int $tokensUsed,
    ) {}

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
