<?php

declare(strict_types=1);

namespace App\Controllers;

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
        $offers = $this->model->findAllActive();

        $this->render('offer/index', [
            'title' => 'Toutes les offres - HexaJobs',
            'offers' => $offers,
        ]);
    }

    public function show(string $slug): void
    {
        $offer = $this->model->findOneActiveBySlug($slug);

        if ($offer === null) {
            $this->abort(404);
        }

        $hasApplied = false;

        if ($this->isAuthenticated()) {
            $user = $this->getUser();

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
}
