<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\CompanyModel;

final class AdminCompanyController extends Controller
{
    private CompanyModel $model;

    public function __construct()
    {
        $this->model = new CompanyModel();
    }

    public function index(): void 
    {
        $this->requireRole('ROLE_ADMIN');

        $this->render('admin_company/index', [
            'pageTitle' => 'Entreprises',
            'companies' => $this->model->findAllForAdmin(),
        ]);
    }
}
