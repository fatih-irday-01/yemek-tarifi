<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\RecipeAnalysisRepositoryInterface;
use App\Repositories\Eloquent\RecipeAnalysisRepository;
use App\Services\AI\Adapters\GroqRecipeAnalyzer;
use App\Services\AI\Contracts\RecipeAnalyzerInterface;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(RecipeAnalysisRepositoryInterface::class, RecipeAnalysisRepository::class);

        $this->app->bind(RecipeAnalyzerInterface::class, GroqRecipeAnalyzer::class);
    }
}
