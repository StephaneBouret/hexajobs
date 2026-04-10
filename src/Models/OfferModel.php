<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Offer;
use PDO;

final class OfferModel extends Model
{
    public function findLatestActiveOffers(int $limit = 5): array
    {
        $sql = <<<SQL
            SELECT o.*, 
                c.name AS company_name, 
                cat.name AS category_name 
            FROM offer o 
            INNER JOIN company c ON c.id_company = o.id_company 
            INNER JOIN category cat ON cat.id_category = o.id_category 
            WHERE o.status = :status 
            ORDER BY o.created_at DESC, o.id_offer DESC 
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }

    /**
     * @return Offer[]
     */
    public function findAllActive(): array
    {
        $sql = <<<SQL
            SELECT o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            WHERE o.status = :status
            ORDER BY o.created_at DESC, o.id_offer DESC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }

    public function findOneActiveBySlug(string $slug): ?Offer
    {
        $sql = <<<SQL
            SELECT o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            WHERE o.slug = :slug
              AND o.status = :status
            LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? Offer::createAndHydrate($row) : null;
    }
}
