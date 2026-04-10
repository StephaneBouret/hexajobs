<?php 

declare(strict_types=1);

namespace App\Controllers;

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

        $this->render('offer/show', [
            'title' => $offer->getTitle() . ' - HexaJobs', 
            'offer' => $offer,
        ]);
    }
}
