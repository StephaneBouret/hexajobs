<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Category;
use PDO;

final class CategoryModel extends Model
{
    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM category ORDER BY name ASC';

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Category => Category::createAndHydrate($row),
            $rows
        );
    }

    public function exists(int $idCategory): bool
    {
        $sql = 'SELECT COUNT(*) FROM category WHERE id_category = :id_category';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_category' => $idCategory,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * @return Category[]
     */
    public function findAllForAdmin(): array
    {
        $sql = <<<SQL
            SELECT
                c.*,
                COUNT(o.id_offer) AS offers_count
            FROM category c
            LEFT JOIN offer o ON o.id_category = c.id_category
            GROUP BY c.id_category
            ORDER BY c.name ASC
            SQL;

        $stmt = $this->pdo->query($sql);

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Category => Category::createAndHydrate($row),
            $rows
        );
    }

    public function findById(int $idCategory): ?Category
    {
        $sql = 'SELECT * FROM category WHERE id_category = :id_category LIMIT 1';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id_category' => $idCategory,
        ]);

        $row = $stmt->fetch();

        return $row ? Category::createAndHydrate($row) : null;
    }

    public function create(Category $category): int
    {
        $sql = <<<SQL
            INSERT INTO category (
                name, 
                slug
            ) VALUES (
                :name, 
                :slug
            )
        SQL;

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Category $category): bool
    {
        $sql = <<<SQL
            UPDATE category
            SET
                name = :name,
                slug = :slug
            WHERE id_category = :id_category
        SQL;

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'id_category' => $category->getIdCategory(),
        ]);
    }

    public function delete(int $idCategory): bool
    {
        $sql = 'DELETE FROM category WHERE id_category = :id_category';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_category' => $idCategory,
        ]);
    }

    public function countOffers(int $idCategory): int
    {
        $sql = 'SELECT COUNT(*) FROM offer WHERE id_category = :id_category';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id_category' => $idCategory,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM category WHERE slug = :slug';

        if ($ignoreId !== null) {
            $sql .= ' AND id_category != :id_category';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);

        if ($ignoreId !== null) {
            $stmt->bindValue(':id_category', $ignoreId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    public function makeUniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
