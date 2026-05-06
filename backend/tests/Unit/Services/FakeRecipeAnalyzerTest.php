<?php

declare(strict_types=1);

use App\DTOs\RecipeAnalysisResult;
use App\Services\AI\Adapters\FakeRecipeAnalyzer;

test('fake analyzer geçerli result döner', function (): void {
    $analyzer = new FakeRecipeAnalyzer();
    $result = $analyzer->analyze('base64data', 'image/jpeg');

    expect($result)->toBeInstanceOf(RecipeAnalysisResult::class)
        ->and($result->foodName)->not->toBeEmpty()
        ->and($result->ingredients)->toBeArray()->not->toBeEmpty()
        ->and($result->steps)->toBeArray()->not->toBeEmpty();
});

test('result toArray yemek adını içermez (food_name ayrı kaydedilir)', function (): void {
    $analyzer = new FakeRecipeAnalyzer();
    $result = $analyzer->analyze('base64data', 'image/jpeg');

    $array = $result->toArray();

    expect($array)->toHaveKeys(['ingredients', 'steps', 'cooking_time', 'servings'])
        ->and($array)->not->toHaveKey('ai_model')
        ->and($array)->not->toHaveKey('tokens_used');
});
