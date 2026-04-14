<?php

declare(strict_types=1);

namespace App\Service;

final class PasswordService
{
    /**
     * Hash un mot de passe en utilisant l'algorithme par défaut de PHP
     */
    public function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    /**
     * Vérifie qu'un mot de passe correspond à son hash
     */
    public function verify(string $plainPassword, string $passwordHash): bool
    {
        return password_verify($plainPassword, $passwordHash);
    }

    /**
     * Vérifie si un hash doit être recaiculé (changement d'algorithme)
     */
    public function needsRehash(string $passwordHash): bool
    {
        return password_needs_rehash($passwordHash, PASSWORD_DEFAULT);
    }
}