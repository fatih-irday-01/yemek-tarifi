<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Contracts\RecipeAnalysisRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 *
 */
final class RecipeHistoryController extends Controller
{
    /**
     * @param RecipeAnalysisRepositoryInterface $repository
     */
    public function __construct(
        private readonly RecipeAnalysisRepositoryInterface $repository,
    ) {}

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $history = $this->repository->paginateForUser(
            userId: $request->user()->id,
            perPage: 15,
        );

        return Inertia::render('Recipe/History', [
            'analyses' => $history,
        ]);
    }
}
