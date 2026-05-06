<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

/**
 * Class RecipeAnalyzerAgent
 */
#[Provider(Lab::Groq)]
#[Model('meta-llama/llama-4-scout-17b-16e-instruct')]
final class RecipeAnalyzerAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return "You are a professional culinary analyst and food photographer expert.
        Analyze the provided image with extreme precision.

        STRICT GUIDELINES:
        1. Identify the dish accurately.
        2. List ALL essential ingredients, including primary proteins (e.g., meat, poultry), fats, and seasonings.
        3. Ensure logical consistency: if it's a meat dish, the 'ingredients' list must include the meat type.
        4. Provide the recipe in Turkish (Türkçe).
        5. Output ONLY the raw JSON string matching the defined schema. No preamble or markdown commentary.";
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'food_name' => $schema->string()->required(),
            'ingredients' => $schema->array()->items($schema->string())->required(),
            'steps' => $schema->array()->items($schema->string())->required(),
            'cooking_time' => $schema->string()->required(),
            'servings' => $schema->integer()->required(),
        ];
    }
}
