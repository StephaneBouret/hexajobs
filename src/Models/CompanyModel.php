<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Company;
use App\Entities\Offer;
use PDO;

final class CompanyModel extends Model
{
    /**
     * @return Company[]
     */
    public function findTopRecruitingCompanies(int $limit = 4): array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                COUNT(o.id_offer) AS offers_count
            FROM company c
            INNER JOIN offer o ON o.id_company = c.id_company
            WHERE o.status = :status
            GROUP BY c.id_company
            ORDER BY offers_count DESC, c.name ASC
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Company => Company::createAndHydrate($row),
            $rows
        );
    }

    public function findAllWithOfferCount(): array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                COUNT(o.id_offer) AS offers_count
            FROM company c
            LEFT JOIN offer o
                ON o.id_company = c.id_company
                AND o.status = :status
            GROUP BY c.id_company
            ORDER BY c.name ASC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Company => Company::createAndHydrate($row),
            $rows
        );
    }

    public function findOneBySlug(string $slug): ?Company
    {
        $sql = <<<SQL
            SELECT
                c.*,
                COUNT(o.id_offer) AS offers_count
            FROM company c
            LEFT JOIN offer o
                ON o.id_company = c.id_company
                AND o.status = :status
            WHERE c.slug = :slug
            GROUP BY c.id_company
            LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? Company::createAndHydrate($row) : null;
    }

    /**
     * @return Offer[]
     */
    public function findActiveOffersByCompany(int $companyId): array
    {
        $sql = <<<SQL
            SELECT
                o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            WHERE o.id_company = :companyId
              AND o.status = :status
            ORDER BY o.created_at DESC, o.id_offer DESC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':companyId', $companyId, PDO::PARAM_INT);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }
}
