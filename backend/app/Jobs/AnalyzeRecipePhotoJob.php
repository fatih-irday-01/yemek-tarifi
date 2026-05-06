<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\AnalyzeRecipePhotoAction;
use App\Models\RecipeAnalysis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
final class AnalyzeRecipePhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public int $tries = 3;
    /**
     * @var int
     */
    public int $timeout = 120;

    /**
     * @param RecipeAnalysis $analysis
     */
    public function __construct(
        public readonly RecipeAnalysis $analysis,
    ) {}

    /**
     * @param AnalyzeRecipePhotoAction $action
     * @return void
     */
    public function handle(AnalyzeRecipePhotoAction $action): void
    {
        $action->execute($this->analysis);
    }
}
