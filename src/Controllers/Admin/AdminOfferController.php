<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\OfferModel;

final class AdminOfferController extends Controller
{
    private OfferModel $model;

    public function __construct()
    {
        $this->model = new OfferModel();
    }

    public function index(): void
    {
        $this->requireRole('ROLE_ADMIN');

        $this->render('admin_offer/index', [
            'pageTitle' => 'Offres',
            'offers' => $this->model->findAllForAdmin(),
        ]);
    }

    public function disable(int $idOffer): void
    {
        $this->requireRole('ROLE_ADMIN');
        $this->requirePost();
        $this->requireCsrf('disable_offer_' . $idOffer);

        $this->model->updateStatus($idOffer, 'inactive');

        $this->setFlash('success', 'Offre désactivée.');
        $this->redirect('/admin/offers');
    }

    public function enable(int $idOffer): void
    {
        $this->requireRole('ROLE_ADMIN');
        $this->requirePost();
        $this->requireCsrf('enable_offer_' . $idOffer);

        $this->model->updateStatus($idOffer, 'active');

        $this->setFlash('success', 'Offre réactivée.');
        $this->redirect('/admin/offers');
    }
}
