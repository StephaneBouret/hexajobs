<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Category;

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
}
