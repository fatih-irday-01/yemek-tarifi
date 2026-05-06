<?php

declare(strict_types=1);

namespace App\Services\AI\Contracts;

use App\DTOs\RecipeAnalysisResult;

interface RecipeAnalyzerInterface
{
    /**
     * @param  string  $disk  Storage disk adı
     * @param  string  $path  Disk üzerindeki dosya yolu
     */
    public function analyze(string $disk, string $path): RecipeAnalysisResult;
}
