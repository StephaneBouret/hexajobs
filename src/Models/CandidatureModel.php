<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Candidature;
use App\Enum\CandidatureStatus;
use PDO;

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

    public function findByOfferAndCompany(int $idOffer, int $idCompany): array
    {
        $sql = <<<SQL
            SELECT
                ca.*,
                o.title AS title,
                o.slug AS slug,
                u.firstname AS user_firstname,
                u.lastname AS user_lastname,
                u.email AS user_email
            FROM candidature ca
            INNER JOIN offer o ON o.id_offer = ca.id_offer
            INNER JOIN user u ON u.id_user = ca.id_user
            WHERE ca.id_offer = :id_offer
            AND o.id_company = :id_company
            ORDER BY ca.created_at DESC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_offer' => $idOffer,
            'id_company' => $idCompany,
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static fn(array $row): Candidature => Candidature::createAndHydrate($row),
            $rows
        );
    }

    public function findOneByIdAndCompany(int $idCandidature, int $idCompany): ?Candidature
    {
        $sql = <<<SQL
            SELECT
                ca.*,
                o.title AS title,
                o.slug AS slug,
                u.firstname AS user_firstname,
                u.lastname AS user_lastname,
                u.email AS user_email
            FROM candidature ca
            INNER JOIN offer o ON o.id_offer = ca.id_offer
            INNER JOIN user u ON u.id_user = ca.id_user
            WHERE ca.id_candidature = :id_candidature
            AND o.id_company = :id_company 
            LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_candidature' => $idCandidature,
            'id_company' => $idCompany,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? Candidature::createAndHydrate($row) : null;
    }

    public function markAsConsulted(int $idCandidature, int $idCompany): bool
    {
        $sql = <<<SQL
            UPDATE candidature ca 
            INNER JOIN offer o ON o.id_offer = ca.id_offer 
            SET ca.status = :status_consultee
            WHERE ca.id_candidature = :id_candidature 
            AND o.id_company = :id_company  
            AND ca.status = :status_en_attente
        SQL;

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'status_consultee' => CandidatureStatus::CONSULTEE->value,
            'status_en_attente' => CandidatureStatus::EN_ATTENTE->value,
            'id_candidature' => $idCandidature,
            'id_company' => $idCompany,
        ]);
    }

    public function acceptAndRejectOthers(int $idCandidature, int $idOffer, int $idCompany): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlAccept = <<<SQL
                UPDATE candidature ca
                INNER JOIN offer o ON o.id_offer = ca.id_offer
                SET ca.status = :status_retenue
                WHERE ca.id_candidature = :id_candidature  
                AND ca.id_offer = :id_offer 
                AND o.id_company = :id_company  
            SQL;

            $stmtAccept = $this->pdo->prepare($sqlAccept);
            $stmtAccept->execute([
                'status_retenue' => CandidatureStatus::RETENUE->value,
                'id_candidature' => $idCandidature,
                'id_offer' => $idOffer,
                'id_company' => $idCompany,
            ]);

            $sqlRejectOthers = <<<SQL
                UPDATE candidature ca
                INNER JOIN offer o ON o.id_offer = ca.id_offer
                SET ca.status = :status_refusee 
                WHERE ca.id_offer = :id_offer 
                AND ca.id_candidature != :id_candidature
                AND o.id_company = :id_company
            SQL;

            $stmtRejectOthers = $this->pdo->prepare($sqlRejectOthers);
            $stmtRejectOthers->execute([
                'status_refusee' => CandidatureStatus::REFUSEE->value,
                'id_offer' => $idOffer,
                'id_candidature' => $idCandidature,
                'id_company' => $idCompany,
            ]);

            $sqlCloseOffer = <<<SQL
                UPDATE offer 
                SET status = 'inactive' 
                WHERE id_offer = :id_offer 
                AND id_company = :id_company
            SQL;

            $stmtCloseOffer = $this->pdo->prepare($sqlCloseOffer);
            $stmtCloseOffer->execute([
                'id_offer' => $idOffer,
                'id_company' => $idCompany,
            ]);

            $this->pdo->commit();

            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function reject(int $idCandidature, int $idCompany): bool
    {
        $sql = <<<SQL
            UPDATE candidature ca
            INNER JOIN offer o ON o.id_offer = ca.id_offer 
            SET ca.status = :status_refusee 
            WHERE ca.id_candidature = :id_candidature  
            AND o.id_company = :id_company
        SQL;

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'status_refusee' => CandidatureStatus::REFUSEE->value,
            'id_candidature' => $idCandidature,
            'id_company' => $idCompany,
        ]);
    }

    public function closeOfferIfFinished(int $idOffer, int $idCompany): void
    {
        $sql = <<<SQL
            SELECT COUNT(*) 
            FROM candidature ca 
            INNER JOIN offer o ON o.id_offer = ca.id_offer 
            WHERE ca.id_offer = :id_offer  
            AND o.id_company = :id_company 
            AND ca.status IN (:en_attente, :consultee)
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_offer' => $idOffer,
            'id_company' => $idCompany,
            'en_attente' => CandidatureStatus::EN_ATTENTE->value,
            'consultee' => CandidatureStatus::CONSULTEE->value,
        ]);

        if ((int) $stmt->fetchColumn() > 0) {
            return;
        }

        $sqlUpdate = <<<SQL
            UPDATE offer
            SET status = 'inactive'
            WHERE id_offer = :id_offer
            AND id_company = :id_company
        SQL;

        $stmtUpdate = $this->pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            'id_offer' => $idOffer,
            'id_company' => $idCompany,
        ]);
    }
}
