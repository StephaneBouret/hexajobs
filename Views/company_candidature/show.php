<?php 
/** @var \App\Entities\Candidature $candidature */
/** @var \App\Service\PdfUploader $pdfUploader */
?>
<section class="py-4">
    <div class="mb-4">
        <a
            href="/entreprise/offres/<?= (int) $candidature->getIdOffer(); ?>/candidatures"
            class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>Retour aux candidatures
        </a>

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h1 class="mb-2">
                    <?= htmlspecialchars($candidature->getUserFullName(), ENT_QUOTES, 'UTF-8'); ?>
                </h1>
                <p class="text-secondary mb-0">
                    Candidature pour :
                    <?= htmlspecialchars($candidature->getOfferTitle(), ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>

            <span class="badge text-bg-<?= htmlspecialchars($candidature->getStatusEnum()->badgeClass(), ENT_QUOTES, 'UTF-8'); ?> fs-6">
                <?= htmlspecialchars($candidature->getStatusEnum()->label(), ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3">Lettre de motivation</h2>

                    <p class="mb-0" style="white-space: pre-line;">
                        <?= htmlspecialchars($candidature->getCoverLetter(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Informations candidat</h2>

                    <p class="mb-2">
                        <strong>Nom :</strong>
                        <?= htmlspecialchars($candidature->getUserFullName(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p class="mb-3">
                        <strong>Email :</strong>
                        <?= htmlspecialchars($candidature->getUserEmail(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <?php $cvUrl = $pdfUploader->getPublicUrl($candidature->getCv()); ?>
                    <?php if ($cvUrl !== null): ?>
                        <a
                            href="<?= htmlspecialchars($cvUrl, ENT_QUOTES, 'UTF-8'); ?>"
                            class="btn btn-outline-primary w-100 mb-3"
                            target="_blank"
                            rel="noopener noreferrer">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Télécharger le CV
                        </a>
                    <?php endif; ?>

                    <hr>

                    <div class="d-grid gap-2">
                        <?php if ($candidature->getStatusEnum() !== \App\Enum\CandidatureStatus::RETENUE): ?>
                            <form
                                method="post"
                                action="/entreprise/candidatures/<?= (int) $candidature->getIdCandidature(); ?>/retenir">
                                <input
                                    type="hidden"
                                    name="_token"
                                    value="<?= htmlspecialchars(\App\Core\Csrf::token('accept_candidature_' . $candidature->getIdCandidature()), ENT_QUOTES, 'UTF-8'); ?>">

                                <button
                                    class="btn btn-success w-100"
                                    onclick="return confirm('Retenir cette candidature ? Les autres candidatures seront refusées.');">
                                    Retenir cette candidature
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if ($candidature->getStatusEnum() !== \App\Enum\CandidatureStatus::REFUSEE): ?>
                            <form
                                method="post"
                                action="/entreprise/candidatures/<?= (int) $candidature->getIdCandidature(); ?>/refuser">
                                <input
                                    type="hidden"
                                    name="_token"
                                    value="<?= htmlspecialchars(\App\Core\Csrf::token('reject_candidature_' . $candidature->getIdCandidature()), ENT_QUOTES, 'UTF-8'); ?>">

                                <button class="btn btn-outline-danger w-100">
                                    Refuser cette candidature
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>