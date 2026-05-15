<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Entities\Category;
use App\Models\CategoryModel;
use App\Service\FormValidator;
use Cocur\Slugify\Slugify;

final class AdminCategoryController extends Controller
{
    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function index(): void
    {
        $this->requireRole('ROLE_ADMIN');

        $this->render('admin_category/index', [
            'pageTitle' => 'Catégories',
            'categories' => $this->model->findAllForAdmin(),
        ]);
    }

    public function create(): void
    {
        $this->requireRole('ROLE_ADMIN');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('admin_category/create', [
                'pageTitle' => 'Créer une catégorie',
                'errors' => [],
                'old' => [
                    'name' => ''
                ],
            ]);
            return;
        }

        $this->requirePost();
        $this->requireCsrf('create_category');

        $validator = new FormValidator($_POST);

        $validator
            ->required('name', 'Le nom est obligatoire.')
            ->minLength('name', 2, 'Minimum 2 caractères.');

        $name = trim((string) ($_POST['name'] ?? ''));

        if ($validator->hasErrors()) {
            $this->render('admin_category/create', [
                'pageTitle' => 'Créer une catégorie',
                'errors' => $validator->getErrors(),
                'old' => [
                    'name' => $name,
                ],
            ]);
            return;
        }

        $slugify = new Slugify();

        $baseSlug = $slugify->slugify($name);
        $slug = $this->model->makeUniqueSlug($baseSlug);

        $category = new Category();
        $category->setName(ucfirst($name))
            ->setSlug($slug);

        $this->model->create($category);

        $this->setFlash('success', 'Catégorie créée.');
        $this->redirect('/admin/categories');
    }

    public function edit(int $idCategory): void
    {
        $this->requireRole('ROLE_ADMIN');

        $category = $this->model->findById($idCategory);

        if ($category === null) {
            $this->abort(404, 'Catégorie introuvable.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('admin_category/edit', [
                'pageTitle' => 'Modifier une catégorie',
                'category' => $category,
                'errors' => [],
                'old' => [
                    'name' => $category->getName(),
                ],
            ]);

            return;
        }

        $this->requirePost();
        $this->requireCsrf('edit_category_' . $idCategory);

        $validator = new FormValidator($_POST);

        $validator
            ->required('name', 'Le nom est obligatoire.')
            ->minLength('name', 2, 'Minimum 2 caractères.');

        $name = trim((string) ($_POST['name'] ?? ''));

        if ($validator->hasErrors()) {
            $this->render('admin_category/edit', [
                'pageTitle' => 'Modifier une catégorie',
                'category' => $category,
                'errors' => $validator->getErrors(),
                'old' => [
                    'name' => $name,
                ],
            ]);
            return;
        }

        $slugify = new Slugify();

        $baseSlug = $slugify->slugify($name);
        $slug = $this->model->makeUniqueSlug($baseSlug, $idCategory);

        $category->setName(ucfirst($name))
            ->setSlug($slug);

        $this->model->update($category);

        $this->setFlash('success', 'Catégorie modifiée.');
        $this->redirect('/admin/categories');
    }

    public function delete(int $idCategory): void
    {
        $this->requireRole('ROLE_ADMIN');
        $this->requirePost();
        $this->requireCsrf('delete_category_' . $idCategory);

        $category = $this->model->findById($idCategory);

        if ($category === null) {
            $this->abort(404, 'Catégorie introuvable.');
        }

        if ($this->model->countOffers($idCategory) > 0) {
            $this->setFlash(
                'warning', 
                'Impossible de supprimer une catégorie liée à des offres.'
            );
            $this->redirect('/admin/categories');
        }

        $this->model->delete($idCategory);
        $this->setFlash('success', 'Catégorie supprimée.');
        $this->redirect('/admin/categories');
    }
}
