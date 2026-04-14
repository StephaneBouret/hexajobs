<?php 

declare(strict_types=1);

namespace App\Security;

final class Roles
{
    public const USER = 'ROLE_USER';
    public const COMPANY = 'ROLE_COMPANY';
    public const ADMIN = 'ROLE_ADMIN';

    public const ALL = [
        self::USER, 
        self::COMPANY, 
        self::ADMIN,
    ];

    public const HIERARCHY = [
        self::ADMIN   => [self::ADMIN, self::COMPANY, self::USER],
        self::COMPANY => [self::COMPANY, self::USER],
        self::USER    => [self::USER],
    ];

    public static function isValid(string $role): bool 
    {
        return in_array($role, self::ALL, true);
    }

    public static function can(string $currentRole, string $requiredRole): bool
    {
        return in_array($requiredRole, self::HIERARCHY[$currentRole] ?? [], true);
    }
}