<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\User;
use PDO;

final class UserModel extends Model
{
    public function findByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM user WHERE email = :email LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', mb_strtolower(trim($email), 'UTF-8'));
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? User::createAndHydrate($data) : null;
    }

    public function findById(int $idUser): ?User
    {
        $sql = 'SELECT * FROM user WHERE id_user = :id_user LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? User::createAndHydrate($data) : null;
    }

    public function emailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', mb_strtolower(trim($email), 'UTF-8'));
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(User $user): int
    {
        $sql = <<<SQL
            INSERT INTO user (
                firstname,
                lastname,
                email,
                password,
                role,
                created_at,
                id_company
            ) VALUES (
                :firstname,
                :lastname,
                :email,
                :password,
                :role,
                :created_at,
                :id_company
            )
            SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':firstname', $user->getFirstname());
        $stmt->bindValue(':lastname', $user->getLastname());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':role', $user->getRole());
        $stmt->bindValue(':created_at', $user->getCreatedAt()?->format('Y-m-d H:i:s'));
        $stmt->bindValue(':id_company', $user->getIdCompany(), $user->getIdCompany() === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function updatePassword(int $idUser, string $newHash): bool
    {
        $sql = 'UPDATE user SET password = :password WHERE id_user = :id_user';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':password', $newHash);
        $stmt->bindValue(':id_user', $idUser, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
