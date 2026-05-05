<?php 
/** @var \App\Entities\Offer $offer */
?>
<section class="py-4">
    <div class="mb-4">
        <a href="/entreprise/offres" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>Retour à mes offres
        </a>

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h1 class="mb-2"><?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h1>
                <p class="text-secondary mb-0">
                    Aperçu recruteur de votre annonce.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a
                    href="/offres/<?= urlencode($offer->getSlug()); ?>"
                    class="btn btn-outline-secondary"
                    target="_blank"
                    rel="noopener noreferrer">
                    Voir côté public
                </a>

                <a
                    href="/entreprise/offres/<?= (int) $offer->getIdOffer(); ?>/edit"
                    class="btn btn-primary">
                    Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="badge text-bg-light border">
                    <?= htmlspecialchars($offer->getCategoryName(), ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <span class="badge text-bg-info">
                    <?= htmlspecialchars($offer->getContractEnum()->label(), ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <?php if ($offer->getStatus() === 'active'): ?>
                    <span class="badge text-bg-success">Active</span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">Inactive</span>
                <?php endif; ?>
            </div>

            <p class="text-secondary mb-2">
                <i class="bi bi-building me-2"></i>
                <?= htmlspecialchars($offer->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <p class="text-secondary mb-2">
                <i class="bi bi-geo-alt me-2"></i>
                <?= htmlspecialchars($offer->getLocation(), ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <p class="text-secondary mb-4">
                <i class="bi bi-cash-stack me-2"></i>
                <?= htmlspecialchars($offer->getSalary(), ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <?php if ($offer->getCreatedAt() !== null): ?>
                <p class="text-secondary mb-4">
                    <i class="bi bi-calendar3 me-2"></i>
                    Publiée le <?= htmlspecialchars($offer->getCreatedAt()->format('d/m/Y'), ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <hr>

            <div class="mt-4">
                <h2 class="h4 mb-3">Description du poste</h2>
                <p class="mb-0" style="white-space: pre-line;">
                    <?= htmlspecialchars($offer->getDescription(), ENT_QUOTES, 'UTF-8'); ?>
                </p>
            </div>
        </div>
    </div>
</section>