<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\RecipeAnalysis;
use App\Repositories\Contracts\RecipeAnalysisRepositoryInterface;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            $imageContent = Storage::disk($analysis->photo_disk)->get($analysis->photo_path);

            if ($imageContent === null) {
                throw new RuntimeException('Fotoğraf dosyası bulunamadı.');
            }

            $mimeType = $this->detectMimeType($analysis->photo_path);
            $imageBase64 = base64_encode($imageContent);

            $result = $this->analyzer->analyze($imageBase64, $mimeType);

            $this->repository->markAsCompleted($analysis->id, $result);
        } catch (RuntimeException $e) {
            Log::error('Recipe analysis failed', [
                'analysis_id' => $analysis->id,
                'error' => $e->getMessage(),
            ]);

            $this->repository->markAsFailed($analysis->id, 'Analiz sırasında bir hata oluştu.');
        }
    }

    private function detectMimeType(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
    }
}
