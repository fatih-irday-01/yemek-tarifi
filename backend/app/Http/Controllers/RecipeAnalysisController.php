<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AnalysisStatus;
use App\Http\Requests\AnalyzeRecipeRequest;
use App\Jobs\AnalyzeRecipePhotoJob;
use App\Repositories\Contracts\RecipeAnalysisRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 *
 */
final class RecipeAnalysisController extends Controller
{
    /**
     * @param RecipeAnalysisRepositoryInterface $repository
     */
    public function __construct(
        private readonly RecipeAnalysisRepositoryInterface $repository,
    ) {}

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Recipe/Upload');
    }

    /**
     * @param AnalyzeRecipeRequest $request
     * @return RedirectResponse
     */
    public function store(AnalyzeRecipeRequest $request): RedirectResponse
    {
        $path = $request->file('photo')->store('recipes/'.$request->user()->id, 'public');

        $analysis = $this->repository->create([
            'user_id' => $request->user()->id,
            'photo_path' => $path,
            'photo_disk' => 'public',
            'status' => AnalysisStatus::Pending,
        ]);

        dispatch(new AnalyzeRecipePhotoJob($analysis));

        return redirect()->route('recipes.show', $analysis->id);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id): Response
    {
        $analysis = $this->repository->findByIdForUser($id, $request->user()->id);

        abort_if($analysis === null, 404);

        return Inertia::render('Recipe/Show', [
            'analysis' => $analysis,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function status(Request $request, int $id): JsonResponse
    {
        $analysis = $this->repository->findByIdForUser($id, $request->user()->id);

        abort_if($analysis === null, 404);

        return response()->json([
            'status' => $analysis->status,
            'food_name' => $analysis->food_name,
            'recipe' => $analysis->recipe,
            'failed' => $analysis->status === AnalysisStatus::Failed,
        ]);
    }
}
