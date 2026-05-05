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

    public function findByCompany(int $idCompany): array
    {
        $sql = <<<SQL
            SELECT o.*,
                c.name AS company_name,
                cat.name AS category_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            INNER JOIN category cat ON cat.id_category = o.id_category
            WHERE o.id_company = :id_company
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
}
