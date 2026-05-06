<?php

declare(strict_types=1);

namespace App\Enums;

/**
 *
 */
enum AnalysisStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Bekliyor',
            self::Processing => 'İşleniyor',
            self::Completed => 'Tamamlandı',
            self::Failed => 'Hata',
        };
    }

    /**
     * @return bool
     */
    public function isTerminal(): bool
    {
        return $this === self::Completed || $this === self::Failed;
    }
}
