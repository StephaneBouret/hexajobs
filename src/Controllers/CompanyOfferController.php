<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Offer;
use App\Enum\ContractType;
use App\Models\CategoryModel;
use App\Models\OfferModel;
use App\Service\FormValidator;
use Cocur\Slugify\Slugify;

final class CompanyOfferController extends Controller
{
    private OfferModel $offerModel;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->offerModel = new OfferModel();
        $this->categoryModel = new CategoryModel();
    }

    public function create(): void
    {
        $this->requireExactRole('ROLE_COMPANY');

        $categories = $this->categoryModel->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('company_offer/create', [
                'pageTitle' => 'Publier une offre',
                'categories' => $categories,
                'errors' => [],
                'old' => $this->emptyOld(),
            ]);
            return;
        }

        $this->requirePost();
        $this->requireCsrf('create_company_offer');

        $form = $this->validateOfferForm();

        if ($form['validator']->hasErrors() || !empty($form['extraErrors'])) {
            $this->render('company_offer/create', [
                'pageTitle' => 'Publier une offre',
                'categories' => $categories,
                'errors' => array_merge(
                    $form['validator']->getErrors(),
                    $form['extraErrors']
                ),
                'old' => $form['old'],
            ]);
            return;
        }

        $user = $this->getUser();
        $idCompany = (int) ($user['id_company'] ?? 0);

        if ($idCompany <= 0) {
            $this->abort(403, 'Compte entreprise invalide.');
        }

        $offer = $this->buildEntity($form, $idCompany);
        $created = $this->offerModel->insert($offer);

        $this->setFlash('success', 'Offre publiée avec succès ✅');
        $this->redirect('/offres/' . $created->getSlug());
    }

    private function validateOfferForm(): array
    {
        $validator = new FormValidator($_POST);

        $validator
            ->required('title', 'Le titre est obligatoire.')
            ->minLength('title', 3, 'Le titre doit contenir au moins 3 caractères.')
            ->maxLength('title', 255, 'Le titre ne doit pas dépasser 255 caractères.')
            ->required('description', 'La description est obligatoire')
            ->minLength('description', 20, 'La description doit contenir au moins 20 caractères.')
            ->required('location', 'La localisation.')
            ->required('contract', 'Le type de contrat est obligatoire.')
            ->required('salary', 'La rémunération est obligatoire.')
            ->required('id_category', 'La catégorie est obligatoire.');

        $title = trim((string) ($_POST['title'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $location = trim((string) ($_POST['location'] ?? ''));
        $contract = trim((string) ($_POST['contract'] ?? ContractType::CDI->value));
        $salary = trim((string) ($_POST['salary'] ?? ''));
        $status = trim((string) ($_POST['status'] ?? 'active'));
        $idCategory = (int) ($_POST['id_category'] ?? 0);

        $extraErrors = [];

        if ($idCategory <= 0 || !$this->categoryModel->exists($idCategory)) {
            $extraErrors['id_category'] = 'La catégorie sélectionnée est invalide.';
        }

        if (ContractType::tryFrom($contract) === null) {
            $extraErrors['contract'] = 'Le type de contrat est invalide.';
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            $extraErrors['status'] = 'Le statut est invalide.';
        }

        return [
            'validator' => $validator,
            'extraErrors' => $extraErrors,
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'contract' => $contract,
            'salary' => $salary,
            'status' => $status,
            'id_category' => $idCategory,
            'old' => [
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'contract' => $contract,
                'salary' => $salary,
                'status' => $status,
                'id_category' => (string) $idCategory,
            ],
        ];
    }

    private function buildEntity(array $form, int $idCompany): Offer
    {
        $slugify = new Slugify();

        $baseSlug = $slugify->slugify($form['title']);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'offre';

        $offer = new Offer();
        $offer->setTitle($form['title']);
        $offer->setSlug($this->offerModel->makeUniqueSlug($baseSlug));
        $offer->setDescription($form['description']);
        $offer->setLocation($form['location']);
        $offer->setContract($form['contract']);
        $offer->setSalary($form['salary']);
        $offer->setStatus($form['status']);
        $offer->setIdCategory($form['id_category']);
        $offer->setIdCompany($idCompany);

        return $offer;
    }

    private function emptyOld(): array
    {
        return [
            'title' => '',
            'description' => '',
            'location' => '',
            'contract' => ContractType::CDI->value,
            'salary' => '',
            'status' => 'active',
            'id_category' => '',
        ];
    }
}
