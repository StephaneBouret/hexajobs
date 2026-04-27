<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Service\AuthService;
use App\Service\FormValidator;
use App\Service\PasswordService;

abstract class AbstractAuthController extends Controller
{
    protected UserModel $userModel;
    protected PasswordService $passwordService;
    protected AuthService $authService;
    // protected LoginAttemptModel $attemptModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->passwordService = new PasswordService();
        $this->authService = new AuthService();
        // $this->attemptModel = new LoginAttemptModel();
    }

    protected function handleLogin(
        ?string $requiredRole, 
        string $view, 
        string $csrfTokenId, 
        string $pageTitle, 
        string $invalidAuthMessage, 
        string $successMessage = 'Connexion réussie ✅',
        string $redirectIfAuthenticatedTo = '/'
    ): void {
        $this->redirectIfAuthenticated($redirectIfAuthenticatedTo);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $redirect = $_GET['redirect'] ?? null;

            if (is_string($redirect) && $redirect !== '') {
                $this->authService->rememberTargetUrl($redirect);
            }

            $this->render($view, [
                'pageTitle' => $pageTitle,
                'old' => [
                    'email' => '',
                ],
                'errors' => [],
            ]);

            return;
        }

        $this->requirePost();
        $this->requireCsrf($csrfTokenId);

        $form = $this->validateLoginForm();

        // ❌ erreurs de validation
        if ($form['validator']->hasErrors()) {
            $this->render($view, [
                'pageTitle' => $pageTitle,
                'errors' => $form['validator']->getErrors(),
                'old' => $form['old'],
            ]);

            return;
        }

        // $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        // // 🚨 Vérification du nombre de tentatives
        // $attempts = $this->attemptModel->countRecentAttempts($form['email'], $ip);

        // if ($attempts >= 5) {
        //     $this->render($view, [
        //         'pageTitle' => $pageTitle,
        //         'errors' => [
        //             'auth' => 'Trop de tentatives. Réessayez dans 15 minutes.'
        //         ],
        //         'old' => $form['old'],
        //     ]);
        //     return;
        // }

        // 🔍 recherche utilisateur
        $user = $this->userModel->findByEmail($form['email']);

        // ❌ erreurs d'authentification
        if (
            $user === null
            || !$this->passwordService->verify($form['password'], $user->getPassword()) 
            || ($requiredRole !== null && $user->getRole() !== $requiredRole)
        ) {
            // ❌ On enregistre la tentative échouée
            // $this->attemptModel->recordFailedAttempt($form['email'], $ip);

            $this->render($view, [
                'pageTitle' => $pageTitle,
                'errors' => [
                    'auth' => $invalidAuthMessage,
                ],
                'old' => $form['old'],
            ]);

            return;
        }

        // ✅ Succès → reset des tentatives
        // $this->attemptModel->clearAttempts($form['email']);

        if ($this->passwordService->needsRehash($user->getPassword())) {
            $newHash = $this->passwordService->hash($form['password']);
            $this->userModel->updatePassword($user->getIdUser(), $newHash);
            $user->setPassword($newHash);
        }

        // $this->userModel->updateLastLogin($user->getId());

        $this->authService->login($user);

        // if ($form['remember_me']) {
        //     $this->authService->enableRememberMe($user, $this->model);
        // }

        $redirectTo = $this->authService->pullTargetUrl('/');
        $this->setFlash('success', $successMessage);
        $this->redirect($redirectTo);
    }

    private function validateLoginForm(): array
    {
        $validator = new FormValidator($_POST);
        $validator
            ->required('email', 'L\'email est obligatoire.')
            ->email('email', 'Le format de l\'email est invalide.')
            ->required('password', 'Le mot de passe est obligatoire.');

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        // $rememberMe = isset($_POST['remember_me']) && $_POST['remember_me'] === '1';

        return [
            'validator' => $validator,
            'email' => $email,
            'password' => $password,
            // 'remember_me' => $rememberMe,
            'old' => [
                'email' => $email,
                // 'remember_me' => $rememberMe ? '1' : '0',
            ],
        ];
    }
}
