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

final class AnalyzeRecipePhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public readonly RecipeAnalysis $analysis,
    ) {}

    public function handle(AnalyzeRecipePhotoAction $action): void
    {
        $action->execute($this->analysis);
    }
}
