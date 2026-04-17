<?php

declare(strict_types=1);

namespace App\Enum;

enum CandidatureStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case CONSULTEE = 'consultee';
    case RETENUE = 'retenue';
    case REFUSEE = 'refusee';

    public function label(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'En attente',
            self::CONSULTEE => 'Consultée',
            self::RETENUE => 'Retenue',
            self::REFUSEE => 'Refusée',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'secondary',
            self::CONSULTEE => 'info',
            self::RETENUE => 'success',
            self::REFUSEE => 'danger',
        };
    }

    public function isFinal(): bool 
    {
        return in_array($this, [self::RETENUE, self::REFUSEE], true);
    }
}
