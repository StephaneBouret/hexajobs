<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\User;
use App\Service\FormValidator;
use App\Service\PasswordValidator;

final class AuthController extends AbstractAuthController
{
    public function register(): void
    {
        $this->redirectIfAuthenticated('/');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'old' => [
                    'firstname' => '',
                    'lastname' => '',
                    'email' => '',
                ],
                'errors' => [],
            ]);
            return;
        }

        $this->requirePost();
        $this->requireCsrf('register');

        $form = $this->validateRegisterForm();

        $errors = array_merge(
            $form['validator']->getErrors(),
            $form['extraErrors']
        );

        if (!empty($errors)) {
            $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'errors' => $errors,
                'old' => $form['old'],
            ]);
            return;
        }

        if ($this->userModel->emailExists($form['email'])) {
            $this->render('auth/register', [
                'pageTitle' => 'Inscription',
                'errors' => ['email' => 'Un compte existe déjà avec cet email.'],
                'old' => $form['old'],
            ]);
            return;
        }

        $user = $this->buildUserEntity(
            firstname: $form['firstname'],
            lastname: $form['lastname'],
            email: $form['email'],
            password: $this->passwordService->hash($form['password']),
        );

        $userId = $this->userModel->create($user);
        $user->setIdUser($userId);

        $this->authService->login($user);

        $redirectTo = $this->authService->pullTargetUrl('/');
        $this->setFlash('success', 'Compte créé, bienvenue ✅');
        $this->redirect($redirectTo);
    }

    public function login(): void
    {
        $this->handleLogin(
            requiredRole: null, // accepte USER + ADMIN
            view: 'auth/login',
            csrfTokenId: 'login',
            pageTitle: 'Connexion',
            invalidAuthMessage: 'Email ou mot de passe incorrect.',
            successMessage: 'Connexion réussie ✅'
        );
    }

    public function logout(): void
    {
        $this->requirePost();
        $this->requireCsrf('logout');

        // $userId = $this->authService->id();

        // $this->authService->clearRememberMe($this->model, $userId);
        $this->authService->logout();

        $this->setFlash('success', 'Déconnexion réussie.');
        $this->redirect('/login');
    }

    private function validateRegisterForm(): array
    {
        $validator = new FormValidator($_POST);

        $validator
            ->required('firstname', 'Le prénom est obligatoire.')
            ->minLength('firstname', 2, 'Le prénom doit contenir au moins 2 caractères.')
            ->maxLength('firstname', 100, 'Le prénom ne doit pas dépasser 100 caractères.')
            ->required('lastname', 'Le nom est obligatoire.')
            ->minLength('lastname', 2, 'Le nom doit contenir au moins 2 caractères.')
            ->maxLength('lastname', 100, 'Le nom ne doit pas dépasser 100 caractères.')
            ->required('email', 'L\'email est obligatoire.')
            ->email('email', 'Le format de l\'email est invalide.')
            ->maxLength('email', 180, 'L\'email ne doit pas dépasser 180 caractères.');

        $firstname = trim((string) ($_POST['firstname'] ?? ''));
        $lastname = trim((string) ($_POST['lastname'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        $extraErrors = [];

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
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
            'old' => [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
            ],
        ];
    }

    private function buildUserEntity(
        string $firstname,
        string $lastname,
        string $email,
        string $password,
    ): User {
        $user = new User();
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole('ROLE_USER');

        return $user;
    }
}
