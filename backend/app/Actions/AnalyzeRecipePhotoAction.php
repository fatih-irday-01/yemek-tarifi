<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\RecipeAnalysis;
use App\Repositories\Contracts\RecipeAnalysisRepositoryInterface;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;
use Illuminate\Support\Facades\Log;
use RuntimeException;

final class AnalyzeRecipePhotoAction
{
    public function __construct(
        private readonly RecipeAnalyzerInterface $analyzer,
        private readonly RecipeAnalysisRepositoryInterface $repository,
    ) {}

    public function execute(RecipeAnalysis $analysis): void
    {
        $this->repository->markAsProcessing($analysis->id);

        try {
            $result = $this->analyzer->analyze($analysis->photo_disk, $analysis->photo_path);

            $this->repository->markAsCompleted($analysis->id, $result);
        } catch (RuntimeException $e) {
            Log::error('Recipe analysis failed', [
                'analysis_id' => $analysis->id,
                'error' => $e->getMessage(),
            ]);

            $this->repository->markAsFailed($analysis->id, 'Analiz sırasında bir hata oluştu.');
        }
    }
}
