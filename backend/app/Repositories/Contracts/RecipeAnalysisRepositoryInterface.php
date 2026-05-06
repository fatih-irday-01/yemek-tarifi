<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\RecipeAnalysisResult;
use App\Enums\AnalysisStatus;
use App\Models\RecipeAnalysis;
use Illuminate\Pagination\LengthAwarePaginator;

interface RecipeAnalysisRepositoryInterface
{
    public function create(array $data): RecipeAnalysis;

    public function findById(int $id): ?RecipeAnalysis;

    public function findByIdForUser(int $id, int $userId): ?RecipeAnalysis;

    public function markAsProcessing(int $id): void;

    public function markAsCompleted(int $id, RecipeAnalysisResult $result): void;

    public function markAsFailed(int $id, string $errorMessage): void;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
}
