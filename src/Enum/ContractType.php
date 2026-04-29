<?php 

declare(strict_types=1);

namespace App\Enum;

enum ContractType: string 
{
    case CDI = 'CDI';
    case CDD = 'CDD';
    case ALTERNANCE = 'Alternance';
    case STAGE = 'Stage';
    case FREELANCE = 'Freelance';

    public function label(): string 
    {
        return $this->value;
    }

    public static function choices(): array 
    {
        return self::cases();
    }
}