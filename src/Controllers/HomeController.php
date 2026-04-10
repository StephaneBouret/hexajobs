<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\OfferModel;

final class HomeController extends Controller
{
    private OfferModel $offerModel;
    private CompanyModel $companyModel;

    public function __construct()
    {
        $this->offerModel = new OfferModel();
        $this->companyModel = new CompanyModel();
    }

    public function index(): void
    {
        $latestOffers = $this->offerModel->findLatestActiveOffers();
        $topCompanies = $this->companyModel->findTopRecruitingCompanies(4);
        
        $this->render('home/index', [
            'title' => 'Accueil - HexaJobs',
            'latestOffers' => $latestOffers,
            'topCompanies' => $topCompanies,
        ]);
    }
}
