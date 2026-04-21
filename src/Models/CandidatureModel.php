<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Candidature;

final class CandidatureModel extends Model
{
    public function alreadyApplied(int $idUser, int $idOffer): bool
    {
        $sql = 'SELECT COUNT(*) FROM candidature WHERE id_user = :user AND id_offer = :offer';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user' => $idUser,
            'offer' => $idOffer,
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function insert(Candidature $c): void
    {
        $sql = 'INSERT INTO candidature (cv, cover_letter, status, id_offer, id_user) 
                VALUES (:cv, :cover_letter, :status, :offer, :user)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'cv' => $c->getCv(),
            'cover_letter' => $c->getCoverLetter(),
            'status' => $c->getStatus(),
            'offer' => $c->getIdOffer(),
            'user' => $c->getIdUser(),
        ]);
    }

    public function findByUser(int $idUser): array
    {
        $sql = 'SELECT c.*, o.title AS title, o.slug AS slug, comp.name AS company_name  
                FROM candidature c 
                INNER JOIN offer o ON o.id_offer = c.id_offer 
                INNER JOIN company comp On comp.id_company = o.id_company  
                WHERE c.id_user = :user 
                ORDER BY c.created_at DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user' => $idUser]);

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Candidature => Candidature::createAndHydrate($row),
            $rows
        );
    }
}
