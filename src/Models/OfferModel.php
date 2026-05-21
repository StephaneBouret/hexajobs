<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Offer;
use App\Enum\ContractType;
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

    public function findByCompany(int $idCompany): array
    {
        $sql = <<<SQL
            SELECT
                o.*,
                c.name AS company_name,
                cat.name AS category_name,
                COUNT(ca.id_candidature) AS candidatures_count
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            LEFT JOIN candidature ca ON ca.id_offer = o.id_offer
            WHERE o.id_company = :id_company
            GROUP BY o.id_offer
            ORDER BY o.created_at DESC, o.id_offer DESC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_company', $idCompany, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }

    public function findOneByIdAndCompany(int $idOffer, int $idCompany): ?Offer
    {
        $sql = <<<SQL
            SELECT o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            WHERE o.id_offer = :id_offer
              AND o.id_company = :id_company
            LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_offer', $idOffer, PDO::PARAM_INT);
        $stmt->bindValue(':id_company', $idCompany, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? Offer::createAndHydrate($row) : null;
    }

    public function insert(Offer $offer): Offer
    {
        $sql = <<<SQL
            INSERT INTO offer (
                title, 
                slug, 
                description, 
                location, 
                contract, 
                salary, 
                status, 
                created_at, 
                id_category, 
                id_company
            ) VALUES (
                :title, 
                :slug, 
                :description, 
                :location, 
                :contract, 
                :salary, 
                :status, 
                :created_at, 
                :id_category, 
                :id_company
            )
        SQL;

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'title' => $offer->getTitle(),
            'slug' => $offer->getSlug(),
            'description' => $offer->getDescription(),
            'location' => $offer->getLocation(),
            'contract' => $offer->getContract(),
            'salary' => $offer->getSalary(),
            'status' => $offer->getStatus(),
            'created_at' => $offer->getCreatedAt()?->format('Y-m-d H:i:s'),
            'id_category' => $offer->getIdCategory(),
            'id_company' => $offer->getIdCompany(),
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return $this->findById($id);
    }

    public function findById(int $idOffer): ?Offer
    {
        $sql = <<<SQL
            SELECT o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            WHERE o.id_offer = :id_offer
            LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_offer' => $idOffer,
        ]);

        $row = $stmt->fetch();

        return $row ? Offer::createAndHydrate($row) : null;
    }

    public function updateByCompany(int $idOffer, int $idCompany, Offer $offer): ?Offer
    {
        $sql = <<<SQL
            UPDATE offer 
            SET 
                title = :title, 
                slug = :slug, 
                description = :description, 
                location = :location, 
                contract = :contract, 
                salary = :salary, 
                status = :status, 
                id_category = :id_category 
            WHERE id_offer = :id_offer
              AND id_company = :id_company
        SQL;

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'title' => $offer->getTitle(),
            'slug' => $offer->getSlug(),
            'description' => $offer->getDescription(),
            'location' => $offer->getLocation(),
            'contract' => $offer->getContract(),
            'salary' => $offer->getSalary(),
            'status' => $offer->getStatus(),
            'id_category' => $offer->getIdCategory(),
            'id_offer' => $idOffer,
            'id_company' => $idCompany,
        ]);

        if ($stmt->rowCount() === 0) {
            return $this->findOneByIdAndCompany($idOffer, $idCompany);
        }

        return $this->findOneByIdAndCompany($idOffer, $idCompany);
    }

    public function deleteByCompany(int $idOffer, int $idCompany): bool
    {
        $sql = 'DELETE FROM offer WHERE id_offer = :id_offer AND id_company = :id_company';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_offer' => $idOffer,
            'id_company' => $idCompany,
        ]);
    }

    public function makeUniqueSlug(string $baseSlug, ?int $ignoreOfferId = null): string
    {
        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreOfferId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreOfferId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM offer WHERE slug = :slug';

        if ($ignoreOfferId !== null) {
            $sql .= ' AND id_offer != :id_offer';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);

        if ($ignoreOfferId !== null) {
            $stmt->bindValue(':id_offer', $ignoreOfferId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * @return Offer[]
     */
    public function findAllForAdmin(): array
    {
        $sql = <<<SQL
            SELECT
                o.*,
                c.name AS company_name,
                cat.name AS category_name,
                COUNT(ca.id_candidature) AS candidatures_count
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            LEFT JOIN candidature ca ON ca.id_offer = o.id_offer
            GROUP BY o.id_offer
            ORDER BY o.created_at DESC, o.id_offer DESC
        SQL;

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }

    public function updateStatus(int $idOffer, string $status): bool
    {
        $sql = 'UPDATE offer SET status = :status WHERE id_offer = :id_offer';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'status' => $status,
            'id_offer' => $idOffer,
        ]);
    }

    /**
     * @return Offer[]
     */
    public function searchActive(array $criteria = [], int $limit = 10, int $offset = 0): array
    {
        $sql = <<<SQL
            SELECT
                o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category 
            WHERE o.status = :status
        SQL;

        $params = [
            'status' => 'active',
        ];

        $this->applyActiveSearchCriteria($sql, $params, $criteria);

        $sql .= ' ORDER BY o.created_at DESC, o.id_offer DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }

    public function countActiveSearch(array $criteria = []): int
    {
        $sql = <<<SQL
            SELECT COUNT(*)
            FROM offer o
            WHERE o.status = :status
        SQL;

        $params = ['status' => 'active'];

        $this->applyActiveSearchCriteria($sql, $params, $criteria);

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function suggestTitles(string $query, int $limit = 6): array
    {
        $sql = <<<SQL
            SELECT DISTINCT title 
            FROM offer 
            WHERE status = :status
              AND title LIKE :query 
            ORDER BY title ASC 
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function suggestLocations(string $query, int $limit = 6): array
    {
        $sql = <<<SQL
            SELECT DISTINCT location
            FROM offer
            WHERE status = :status
            AND location LIKE :query
            ORDER BY location ASC
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', 'active', PDO::PARAM_STR);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function applyActiveSearchCriteria(string &$sql, array &$params, array $criteria): void
    {
        if (!empty($criteria['q'])) {
            $sql .= ' AND o.title LIKE :q';
            $params['q'] = '%' . $criteria['q'] . '%';
        }

        if (!empty($criteria['location'])) {
            $sql .= ' AND o.location LIKE :location';
            $params['location'] = '%' . $criteria['location'] . '%';
        }

        if (!empty($criteria['contracts']) && is_array($criteria['contracts'])) {
            $allowed = array_map(
                static fn(ContractType $type): string => $type->value,
                ContractType::cases()
            );

            $contracts = array_values(array_intersect($criteria['contracts'], $allowed));

            if ($contracts !== []) {
                $placeholders = [];

                foreach ($contracts as $index => $contract) {
                    $key = 'contract_' . $index;
                    $placeholders[] = ':' . $key;
                    $params[$key] = $contract;
                }

                $sql .= ' AND o.contract IN (' . implode(', ', $placeholders) . ')';
            }
        }
    }
}
