<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnalysisStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RecipeAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_path',
        'photo_disk',
        'status',
        'food_name',
        'recipe',
        'error_message',
        'ai_model',
        'tokens_used',
    ];

    protected $casts = [
        'recipe' => 'array',
        'status' => AnalysisStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === AnalysisStatus::Pending;
    }

    public function isCompleted(): bool
    {
        return $this->status === AnalysisStatus::Completed;
    }

    public function isFailed(): bool
    {
        return $this->status === AnalysisStatus::Failed;
    }
}
