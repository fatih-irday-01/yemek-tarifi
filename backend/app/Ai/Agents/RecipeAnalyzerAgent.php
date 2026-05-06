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
#[Model('llama-3.2-90b-vision-preview')]
final class RecipeAnalyzerAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return 'Sen bir yemek analiz uzmanısın. Verilen fotoğraftaki yemeği tanımla ve Türkçe tarif çıkar. Yanıtı yalnızca istenen JSON formatında ver.';
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
