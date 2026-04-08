<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\OfferModel;

final class HomeController extends Controller
{
    private OfferModel $model;

    public function __construct()
    {
        $this->model = new OfferModel();
    }

    public function index(): void
    {
        $latestOffers = $this->model->findLatestActiveOffers();
        
        $this->render('home/index', [
            'title' => 'Accueil',
            'latestOffers' => $latestOffers,
        ]);
    }
}
