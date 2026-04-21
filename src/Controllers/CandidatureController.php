<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Candidature;
use App\Models\CandidatureModel;
use App\Service\FormValidator;
use App\Service\PdfUploader;

final class CandidatureController extends Controller
{
    private CandidatureModel $model;
    private PdfUploader $uploader;

    public function __construct()
    {
        $this->model = new CandidatureModel();
        $this->uploader = new PdfUploader();
    }

    public function index(): void
    {
        $this->requireExactRole('ROLE_USER');

        $user = $this->getUser();
        $items = $this->model->findByUser((int)$user['id']);

        $this->render('candidature/index', [
            'pageTitle' => 'Mes candidatures',
            'items' => $items,
            'pdfUploader' => $this->uploader,
        ]);
    }

    public function apply(int $idOffer): void
    {
        $this->requireExactRole('ROLE_USER');
        $this->requirePost();
        $this->requireCsrf('apply_' . $idOffer);

        $user = $this->getUser();
        $baseName = trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''));
        $idUser = (int) $user['id'];

        // 🔴 Règle métier
        if ($this->model->alreadyApplied($idUser, $idOffer)) {
            $this->setFlash('warning', 'Vous avez déjà postulé.');
            $this->redirect('/offres/' . $_POST['slug']);
        }

        // 🟡 Validation
        $form = $this->validateForm();

        if ($form['validator']->hasErrors()) {
            $slug = (string) ($_POST['slug'] ?? '');
            $this->storeOldApplication($slug, [
                'cover_letter' => $form['cover_letter'],
            ]);
            $this->setFlash('danger', implode(' ', $form['validator']->getErrors()));
            $this->redirect('/offres/' . $_POST['slug']);
        }

        // 🟡 Upload CV
        try {
            $cv = $this->uploader->upload('cv', $baseName);
        } catch (\RuntimeException $e) {
            $slug = (string) ($_POST['slug'] ?? '');
            $this->storeOldApplication($slug, [
                'cover_letter' => $form['cover_letter'],
            ]);
            $this->setFlash('danger', $e->getMessage());
            $this->redirect('/offres/' . $slug);
        }

        // 🟢 Construction entité
        $candidature = $this->buildEntity(
            $cv,
            $form['cover_letter'],
            $idOffer,
            $idUser
        );

        // 🟢 Insert
        $this->model->insert($candidature);

        $this->setFlash('success', 'Candidature envoyée ✅');
        $this->redirect('/offres/' . $_POST['slug']);
    }

    private function validateForm(): array
    {
        $validator = new FormValidator($_POST);

        $validator
            ->required('cover_letter', 'La lettre est obligatoire')
            ->minLength('cover_letter', 20, 'Minimum 20 caractères.');

        return [
            'validator' => $validator,
            'cover_letter' => trim((string) ($_POST['cover_letter'] ?? '')),
        ];
    }

    private function buildEntity(
        string $cv,
        string $coverLetter,
        int $idOffer,
        int $idUser
    ): Candidature {
        $c = new Candidature();
        $c->setCv($cv);
        $c->setCoverLetter($coverLetter);
        $c->setIdOffer($idOffer);
        $c->setIdUser($idUser);

        return $c;
    }

    private function storeOldApplication(string $slug, array $data): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['_old_application'][$slug] = $data;
    }
}
