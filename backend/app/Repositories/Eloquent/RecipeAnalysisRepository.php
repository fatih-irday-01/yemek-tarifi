<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\RecipeAnalysisResult;
use App\Enums\AnalysisStatus;
use App\Models\RecipeAnalysis;
use App\Repositories\Contracts\RecipeAnalysisRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final class RecipeAnalysisRepository implements RecipeAnalysisRepositoryInterface
{
    public function create(array $data): RecipeAnalysis
    {
        return RecipeAnalysis::create($data);
    }

    public function findById(int $id): ?RecipeAnalysis
    {
        return RecipeAnalysis::find($id);
    }

    public function findByIdForUser(int $id, int $userId): ?RecipeAnalysis
    {
        return RecipeAnalysis::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function markAsProcessing(int $id): void
    {
        RecipeAnalysis::where('id', $id)->update(['status' => AnalysisStatus::Processing]);
    }

    public function markAsCompleted(int $id, RecipeAnalysisResult $result): void
    {
        RecipeAnalysis::where('id', $id)->update([
            'status' => AnalysisStatus::Completed,
            'food_name' => $result->foodName,
            'recipe' => $result->toArray(),
            'ai_model' => $result->aiModel,
            'tokens_used' => $result->tokensUsed,
        ]);
    }

    public function markAsFailed(int $id, string $errorMessage): void
    {
        RecipeAnalysis::where('id', $id)->update([
            'status' => AnalysisStatus::Failed,
            'error_message' => $errorMessage,
        ]);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return RecipeAnalysis::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
