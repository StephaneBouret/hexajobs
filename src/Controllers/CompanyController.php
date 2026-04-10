<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CompanyModel;

final class CompanyController extends Controller
{
    private CompanyModel $model;

    public function __construct()
    {
        $this->model = new CompanyModel();
    }

    public function index(): void
    {
        $companies = $this->model->findAllWithOfferCount();

        $this->render('company/index', [
            'title' => 'Toutes les entreprises - HexaJobs',
            'companies' => $companies,
        ]);
    }

    public function show(string $slug): void
    {
        $company = $this->model->findOneBySlug($slug);

        if ($company === null) {
            $this->abort(404);
        }

        $offers = $this->model->findActiveOffersByCompany((int) $company->getIdCompany());

        $this->render('company/show', [
            'title' => $company->getName() . ' - HexaJobs',
            'company' => $company,
            'offers' => $offers,
        ]);
    }
}
