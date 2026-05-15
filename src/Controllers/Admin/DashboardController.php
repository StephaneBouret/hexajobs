<?php 

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\DashboardModel;

final class DashboardController extends Controller
{
    private DashboardModel $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index(): void 
    {
        $this->requireRole('ROLE_ADMIN');

        $this->render('dashboard/index', [
            'pageTitle' => 'Dashboard admin',
            'stats' => $this->model->getStats(),
            'latestOffers' => $this->model->getLatestOffers(),
            'latestCompanies' => $this->model->getLatestCompanies(),
        ]);
    }
}
