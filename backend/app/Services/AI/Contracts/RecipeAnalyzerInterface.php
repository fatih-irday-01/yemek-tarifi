<?php

declare(strict_types=1);

namespace App\Services\AI\Contracts;

use App\DTOs\RecipeAnalysisResult;

interface RecipeAnalyzerInterface
{
    public function analyze(string $imageBase64, string $mimeType): RecipeAnalysisResult;
}
