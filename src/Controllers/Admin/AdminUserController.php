<?php 

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\UserModel;

final class AdminUserController extends Controller
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index(): void 
    {
        $this->requireRole('ROLE_ADMIN');

        $this->render('admin_user/index', [
            'pageTitle' => 'Utilisateurs',
            'users' => $this->model->findAllForAdmin(),
        ]);
    }
}
