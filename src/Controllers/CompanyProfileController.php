<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Company;
use App\Models\CompanyModel;
use App\Service\FormValidator;

final class CompanyProfileController extends Controller
{
    private CompanyModel $companyModel;

    public function __construct()
    {
        $this->companyModel = new CompanyModel();
    }

    public function edit(): void
    {
        $this->requireExactRole('ROLE_COMPANY');

        $idCompany = $this->requireCompanyId();
        $company = $this->companyModel->findOneById($idCompany);

        if ($company === null) {
            $this->abort(404, 'Entreprise introuvable.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('company_profile/edit', [
                'pageTitle' => 'Modifier mon entreprise',
                'company' => $company,
                'errors' => [],
                'old' => $this->oldFromCompany($company),
            ]);
            return;
        }

        $this->requirePost();
        $this->requireCsrf('edit_company_profile');

        $form = $this->validateProfileForm();

        $errors = array_merge(
            $form['validator']->getErrors(),
            $form['extraErrors']
        );

        if ($form['siret'] !== $company->getSiret() && $this->companyModel->siretExists($form['siret'])) {
            $errors['siret'] = 'Une entreprise existe déjà avec ce SIRET.';
        }

        if (!empty($errors)) {
            $this->render('company_profile/edit', [
                'pageTitle' => 'Modifier mon entreprise',
                'company' => $company,
                'errors' => $errors,
                'old' => $form['old'],
            ]);
            return;
        }

        $updatedCompany = $this->buildCompany($form);
        $this->companyModel->updateProfile($idCompany, $updatedCompany);

        $this->setFlash('success', 'Entreprise modifiée avec succès.');
        $this->redirect('/entreprise/profil');
    }

    private function validateProfileForm(): array
    {
        $validator = new FormValidator($_POST);

        $validator
            ->required('company_name', 'Le nom de l\'entreprise est obligatoire.')
            ->minLength('company_name', 2, 'Le nom de l\'entreprise doit contenir au moins 2 caractères.')
            ->maxLength('company_name', 100, 'Le nom de l\'entreprise ne doit pas dépasser 100 caractères.')
            ->required('siret', 'Le SIRET est obligatoire.')
            ->required('address', 'L\'adresse est obligatoire.')
            ->required('postal_code', 'Le code postal est obligatoire.')
            ->required('city', 'La ville est obligatoire.')
            ->required('description', 'La description est obligatoire.')
            ->minLength('description', 20, 'La description doit contenir au moins 20 caractères.');

        $siret = preg_replace('/\D+/', '', (string) ($_POST['siret'] ?? '')) ?? '';

        $extraErrors = [];

        if ($siret !== '' && !preg_match('/^\d{14}$/', $siret)) {
            $extraErrors['siret'] = 'Le SIRET doit contenir exactement 14 chiffres.';
        }

        return [
            'validator' => $validator,
            'extraErrors' => $extraErrors,
            'company_name' => trim((string) ($_POST['company_name'] ?? '')),
            'siret' => $siret,
            'address' => trim((string) ($_POST['address'] ?? '')),
            'postal_code' => trim((string) ($_POST['postal_code'] ?? '')),
            'city' => trim((string) ($_POST['city'] ?? '')),
            'url' => trim((string) ($_POST['url'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'old' => [
                'company_name' => trim((string) ($_POST['company_name'] ?? '')),
                'siret' => $siret,
                'address' => trim((string) ($_POST['address'] ?? '')),
                'postal_code' => trim((string) ($_POST['postal_code'] ?? '')),
                'city' => trim((string) ($_POST['city'] ?? '')),
                'url' => trim((string) ($_POST['url'] ?? '')),
                'description' => trim((string) ($_POST['description'] ?? '')),
            ],
        ];
    }

    private function buildCompany(array $form): Company
    {
        $company = new Company();
        $company->setName($form['company_name']);
        $company->setSlug(Company::slugify($form['company_name']));
        $company->setSiret($form['siret']);
        $company->setAddress($form['address']);
        $company->setPostalCode($form['postal_code']);
        $company->setCity($form['city']);
        $company->setUrl($form['url'] !== '' ? $form['url'] : null);
        $company->setDescription($form['description']);

        return $company;
    }

    private function oldFromCompany(Company $company): array
    {
        return [
            'company_name' => $company->getName(),
            'siret' => $company->getSiret(),
            'address' => $company->getAddress(),
            'postal_code' => $company->getPostalCode(),
            'city' => $company->getCity(),
            'url' => $company->getUrl() ?? '',
            'description' => $company->getDescription(),
        ];
    }
}