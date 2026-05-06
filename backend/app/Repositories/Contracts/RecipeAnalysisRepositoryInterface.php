<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\RecipeAnalysisResult;
use App\Enums\AnalysisStatus;
use App\Models\RecipeAnalysis;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 *
 */
interface RecipeAnalysisRepositoryInterface
{
    /**
     * @param array $data
     * @return RecipeAnalysis
     */
    public function create(array $data): RecipeAnalysis;

    /**
     * @param int $id
     * @return RecipeAnalysis|null
     */
    public function findById(int $id): ?RecipeAnalysis;

    /**
     * @param int $id
     * @param int $userId
     * @return RecipeAnalysis|null
     */
    public function findByIdForUser(int $id, int $userId): ?RecipeAnalysis;

    /**
     * @param int $id
     * @return void
     */
    public function markAsProcessing(int $id): void;

    /**
     * @param int $id
     * @param RecipeAnalysisResult $result
     * @return void
     */
    public function markAsCompleted(int $id, RecipeAnalysisResult $result): void;

    /**
     * @param int $id
     * @param string $errorMessage
     * @return void
     */
    public function markAsFailed(int $id, string $errorMessage): void;

    /**
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
}
