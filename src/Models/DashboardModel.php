<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Company;
use App\Entities\Offer;
use PDO;

final class DashboardModel extends Model
{
    public function getStats(): array 
    {
        return [
            'users' => $this->countTable('user'),
            'companies' => $this->countTable('company'),
            'offers' => $this->countTable('offer'),
            'activeOffers' => $this->countOffersByStatus('active'),
            'candidatures' => $this->countTable('candidature'),
        ];
    }

    public function getLatestOffers(int $limit = 5): array
    {
        $sql = <<<SQL
            SELECT 
                o.id_offer,
                o.title,
                o.slug,
                o.status,
                o.created_at,
                c.name AS company_name
            FROM offer o
            INNER JOIN company c ON c.id_company = o.id_company
            ORDER BY o.created_at DESC, o.id_offer DESC
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Offer => Offer::createAndHydrate($row),
            $rows
        );
    }

    public function getLatestCompanies(int $limit = 5): array
    {
        $sql = <<<SQL
            SELECT 
                id_company,
                name, 
                slug, 
                city,
                created_at
            FROM company
            ORDER BY created_at DESC, id_company DESC
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map(
            static fn(array $row): Company => Company::createAndHydrate($row),
            $rows
        );
    }

    private function countTable(string $table): int 
    {
        $allowed = ['user', 'company', 'offer', 'candidature'];

        if (!in_array($table, $allowed, true)) {
            throw new \InvalidArgumentException('Table non autorisée.');
        }

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$table}");

        return (int) $stmt->fetchColumn();
    }

    private function countOffersByStatus(string $status): int 
    {
        $sql = 'SELECT COUNT(*) FROM offer WHERE status = :status';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
