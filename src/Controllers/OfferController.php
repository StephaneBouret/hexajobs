<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enum\ContractType;
use App\Models\CandidatureModel;
use App\Models\OfferModel;

final class OfferController extends Controller
{
    private OfferModel $model;

    public function __construct()
    {
        $this->model = new OfferModel();
    }

    public function index(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $location = trim((string) ($_GET['location'] ?? ''));
        $contracts = $_GET['contracts'] ?? [];

        if (!is_array($contracts)) {
            $contracts = [];
        }

        $criteria = [
            'q' => $q,
            'location' => $location,
            'contracts' => $contracts,
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;
        $totalOffers = $this->model->countActiveSearch($criteria);
        $pages = max(1, (int) ceil($totalOffers / $perPage));
        $page = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $offers = $this->model->searchActive($criteria, $perPage, $offset);

        $paginationQuery = [];

        if ($q !== '') {
            $paginationQuery['q'] = $q;
        }

        if ($location !== '') {
            $paginationQuery['location'] = $location;
        }

        if (!empty($contracts)) {
            $paginationQuery['contracts'] = $contracts;
        }

        $this->render('offer/index', [
            'title' => 'Toutes les offres - HexaJobs',
            'offers' => $offers,
            'totalOffers' => $totalOffers,
            'criteria' => $criteria,
            'contractTypes' => ContractType::cases(),
            'pagination' => [
                'page' => $page,
                'pages' => $pages,
                'path' => '/offres',
                'query' => $paginationQuery,
                'window' => 5,
                'label' => 'Pagination des offres',
            ],
        ]);
    }

    public function suggestTitles(): void
    {
        $query = trim((string) ($_GET['q'] ?? ''));

        if (mb_strlen($query) < 2) {
            $this->jsonSuggestions([]);
            return;
        }

        $this->jsonSuggestions(
            $this->model->suggestTitles($query)
        );
    }

    public function suggestLocations(): void
    {
        $query = trim((string) ($_GET['q'] ?? ''));

        if (mb_strlen($query) < 2) {
            $this->jsonSuggestions([]);
            return;
        }

        $this->jsonSuggestions(
            $this->model->suggestLocations($query)
        );
    }

    public function show(string $slug): void
    {
        $offer = $this->model->findOneActiveBySlug($slug);

        if ($offer === null) {
            $this->abort(404);
        }

        $user = $this->getUser();

        $displayApplicationSection = true;

        if ($user !== null && ($user['role'] ?? null) === 'ROLE_ADMIN') {
            $displayApplicationSection = false;
        }

        $hasApplied = false;

        if ($this->isAuthenticated()) {
            if ($user !== null) {
                $candidatureModel = new CandidatureModel();
                $hasApplied = $candidatureModel->alreadyApplied((int) $user['id'], (int) $offer->getIdOffer());
            }
        }

        $this->render('offer/show', [
            'title' => $offer->getTitle() . ' - HexaJobs',
            'offer' => $offer,
            'hasApplied' => $hasApplied,
            'oldApplication' => $this->pullOldApplication($slug),
            'displayApplicationSection' => $displayApplicationSection,
        ]);
    }

    private function pullOldApplication(string $slug): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $old = $_SESSION['_old_application'][$slug] ?? [];
        unset($_SESSION['_old_application'][$slug]);

        return is_array($old) ? $old : [];
    }

    private function jsonSuggestions(array $items): void
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(array_values($items), JSON_UNESCAPED_UNICODE);
    }
}
