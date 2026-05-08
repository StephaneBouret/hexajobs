<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Candidature;
use App\Enum\CandidatureStatus;
use App\Models\CandidatureModel;
use App\Models\OfferModel;
use App\Service\PdfUploader;

final class CompanyCandidatureController extends Controller
{
    private CandidatureModel $candidatureModel;
    private OfferModel $offerModel;
    private PdfUploader $pdfUploader;

    public function __construct()
    {
        $this->candidatureModel = new CandidatureModel();
        $this->offerModel = new OfferModel();
        $this->pdfUploader = new PdfUploader();
    }

    public function index(int $idOffer): void
    {
        $this->requireExactRole('ROLE_COMPANY');

        $idCompany = $this->requireCompanyId();

        $offer = $this->offerModel->findOneByIdAndCompany($idOffer, $idCompany);

        if ($offer === null) {
            $this->abort(404, 'Offre introuvable ou accès interdit.');
        }

        $candidatures = $this->candidatureModel->findByOfferAndCompany($idOffer, $idCompany);

        $this->render('company_candidature/index', [
            'pageTitle' => 'Candidatures reçues',
            'offer' => $offer,
            'candidatures' => $candidatures,
        ]);
    }

    public function show(int $idCandidature): void
    {
        $this->requireExactRole('ROLE_COMPANY');

        $idCompany = $this->requireCompanyId();

        $candidature = $this->getCandidatureOrAbort($idCandidature, $idCompany);

        if ($candidature->getStatusEnum() === CandidatureStatus::EN_ATTENTE) {
            $this->candidatureModel->markAsConsulted($idCandidature, $idCompany);
            $candidature = $this->getCandidatureOrAbort($idCandidature, $idCompany);
        }

        $this->render('company_candidature/show', [
            'pageTitle' => 'Détail candidature',
            'candidature' => $candidature,
            'pdfUploader' => $this->pdfUploader,
        ]);
    }

    public function accept(int $idCandidature): void
    {
        $this->requireExactRole('ROLE_COMPANY');
        $this->requirePost();
        $this->requireCsrf('accept_candidature_' . $idCandidature);

        $idCompany = $this->requireCompanyId();

        $candidature = $this->getCandidatureOrAbort($idCandidature, $idCompany);

        $ok = $this->candidatureModel->acceptAndRejectOthers(
            $idCandidature,
            $candidature->getIdOffer(),
            $idCompany
        );

        $this->setFlash(
            $ok ? 'success' : 'danger',
            $ok
                ? 'Candidature retenue. Les autres candidatures ont été refusées et l\'offre est passée à inactive.'
                : 'Impossible de retenir cette candidature.'
        );

        $this->redirect('/entreprise/offres/' . $candidature->getIdOffer() . '/candidatures');
    }

    public function reject(int $idCandidature): void
    {
        $this->requireExactRole('ROLE_COMPANY');
        $this->requirePost();
        $this->requireCsrf('reject_candidature_' . $idCandidature);

        $idCompany = $this->requireCompanyId();

        $candidature = $this->getCandidatureOrAbort($idCandidature, $idCompany);

        $ok = $this->candidatureModel->reject($idCandidature, $idCompany);

        if ($ok) {
            $this->candidatureModel->closeOfferIfFinished($candidature->getIdOffer(), $idCompany);
        }

        $this->setFlash(
            $ok ? 'success' : 'danger',
            $ok ? 'Candidature refusée.' : 'Impossible de refuser cette candidature.'
        );

        $this->redirect('/entreprise/offres/' . $candidature->getIdOffer() . '/candidatures');
    }

    private function getCandidatureOrAbort(int $idCandidature, int $idCompany): Candidature
    {
        $candidature = $this->candidatureModel->findOneByIdAndCompany($idCandidature, $idCompany);

        if ($candidature === null) {
            $this->abort(404, 'Candidature introuvable ou accès interdit.');
        }

        return $candidature;
    }
}
