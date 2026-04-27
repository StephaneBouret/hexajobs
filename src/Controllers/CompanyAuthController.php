<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Company;
use App\Entities\User;
use App\Models\CompanyModel;
use App\Service\FormValidator;
use App\Service\PasswordValidator;

final class CompanyAuthController extends AbstractAuthController
{
    private CompanyModel $companyModel;

    public function __construct()
    {
        parent::__construct();
        $this->companyModel = new CompanyModel();
    }

    public function register(): void
    {
        $this->redirectIfAuthenticated('/');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('company_auth/register', [
                'pageTitle' => 'Inscription entreprise',
                'old' => [
                    'company_name' => '',
                    'siret' => '',
                    'address' => '',
                    'postal_code' => '',
                    'city' => '',
                    'url' => '',
                    'description' => '',
                    'firstname' => '',
                    'lastname' => '',
                    'email' => '',
                ],
                'errors' => [],
            ]);
            return;
        }

        $this->requirePost();
        $this->requireCsrf('company_register');

        $form = $this->validateRegisterForm();

        $errors = array_merge(
            $form['validator']->getErrors(),
            $form['extraErrors']
        );

        if ($this->userModel->emailExists($form['email'])) {
            $errors['email'] = 'Un compte existe déjà avec cet email.';
        }

        if ($this->companyModel->siretExists($form['siret'])) {
            $errors['siret'] = 'Une entreprise existe déjà avec ce SIRET.';
        }

        if (!empty($errors)) {
            $this->render('company_auth/register', [
                'pageTitle' => 'Inscription entreprise',
                'errors' => $errors,
                'old' => $form['old'],
            ]);
            return;
        }

        $company = $this->buildCompanyEntity(
            name: $form['company_name'],
            siret: $form['siret'],
            address: $form['address'],
            postalCode: $form['postal_code'],
            city: $form['city'],
            url: $form['url'] !== '' ? $form['url'] : null,
            description: $form['description']
        );

        $companyId = $this->companyModel->create($company);
        $company->setIdCompany($companyId);

        $user = $this->buildCompanyUserEntity(
            firstname: $form['firstname'],
            lastname: $form['lastname'],
            email: $form['email'],
            passwordHash: $this->passwordService->hash($form['password']),
            companyId: $companyId
        );

        $userId = $this->userModel->create($user);
        $user->setIdUser($userId);

        $this->authService->login($user);

        $this->setFlash('success', 'Compte entreprise créé avec succès ✅');
        $this->redirect('/');
    }

    public function login(): void
    {
        $this->handleLogin(
            requiredRole: 'ROLE_COMPANY',
            view: 'company_auth/login', 
            csrfTokenId: 'company_login', 
            pageTitle: 'Connexion entreprise', 
            invalidAuthMessage: 'Identifiants invalides pour un compte entreprise.', 
            successMessage: 'Connexion entreprise réussie ✅'
        );
    }

    private function validateRegisterForm(): array
    {
        $validator = new FormValidator($_POST);

        $validator
            ->required('company_name', 'Le nom de l\'entreprise est obligatoire.')
            ->minLength('company_name', 2, 'Le nom de l\'entreprise doit contenir au moins 2 caractères.')
            ->maxLength('company_name', 100, 'Le nom de l\'entreprise ne doit pas dépasser 255 caractères.')
            ->required('siret', 'Le SIRET est obligatoire.')
            ->required('address', 'L\'adresse est obligatoire')
            ->required('postal_code', 'Le code postal est obligatoire.')
            ->required('city', 'La ville est obligatoire')
            ->required('description', 'La description est obligatoire')
            ->minLength('description', 20, 'La description doit contenir au moins 20 caractères.')
            ->required('firstname', 'Le prénom est obligatoire.')
            ->required('lastname', 'Le nom est obligatoire.')
            ->required('email', 'L\'email est obligatoire.')
            ->email('email', 'Le format de l\'email est invalide.');

        $companyName = trim((string) ($_POST['company_name'] ?? ''));
        $siret = preg_replace('/\D+/', '', (string) ($_POST['siret'] ?? '')) ?? '';
        $address = trim((string) ($_POST['address'] ?? ''));
        $postalCode = trim((string) ($_POST['postal_code'] ?? ''));
        $city = trim((string) ($_POST['city'] ?? ''));
        $url = trim((string) ($_POST['url'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $firstname = trim((string) ($_POST['firstname'] ?? ''));
        $lastname = trim((string) ($_POST['lastname'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        $extraErrors = [];

        if ($siret !== '' && !preg_match('/^\d{14}$/', $siret)) {
            $extraErrors['siret'] = 'Le SIRET doit contenir exactement 14 chiffres.';
        }

        if ($password === '') {
            $extraErrors['password'] = 'Le mot de passe est obligatoire.';
        }

        if ($passwordConfirm === '') {
            $extraErrors['password_confirm'] = 'La confirmation du mot de passe est obligatoire.';
        }

        if ($password !== '' && $passwordConfirm !== '' && $password !== $passwordConfirm) {
            $extraErrors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
        }

        if ($password !== '') {
            $passwordErrors = PasswordValidator::validate(
                $password,
                $email,
                $firstname,
                $lastname
            );

            if ($passwordErrors !== []) {
                $extraErrors['password'] = implode(' ', $passwordErrors);
            }
        }

        return [
            'validator' => $validator,
            'extraErrors' => $extraErrors,
            'company_name' => $companyName,
            'siret' => $siret,
            'address' => $address,
            'postal_code' => $postalCode,
            'city' => $city,
            'url' => $url,
            'description' => $description,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'old' => [
                'company_name' => $companyName,
                'siret' => $siret,
                'address' => $address,
                'postal_code' => $postalCode,
                'city' => $city,
                'url' => $url,
                'description' => $description,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
            ],
        ];
    }

    private function buildCompanyEntity(
        string $name,
        string $siret,
        string $address,
        string $postalCode,
        string $city,
        ?string $url,
        string $description
    ): Company {
        $company = new Company();
        $company->setName($name);
        $company->setSlug(Company::slugify($name));
        $company->setSiret($siret);
        $company->setAddress($address);
        $company->setPostalCode($postalCode);
        $company->setCity($city);
        $company->setUrl($url);
        $company->setDescription($description);

        return $company;
    }

    private function buildCompanyUserEntity(
        string $firstname,
        string $lastname,
        string $email,
        string $passwordHash,
        int $companyId
    ): User {
        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($passwordHash);
        $user->setRole('ROLE_COMPANY');
        $user->setIdCompany($companyId);

        return $user;
    }
}
