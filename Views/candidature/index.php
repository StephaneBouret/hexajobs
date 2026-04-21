<section class="py-4">
    <div class="mb-4">
        <h1 class="mb-2">Mes candidatures</h1>
        <p class="text-secondary mb-0">
            Retrouvez ici l'historique de vos candidatures envoyées.
        </p>
    </div>

    <?php if (empty($items)): ?>
        <div class="alert alert-info border-0 shadow-sm rounded-4">
            Vous n'avez encore envoyé aucune candidature.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($items as $candidature): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <span class="badge text-bg-<?= htmlspecialchars($candidature->getStatusEnum()->badgeClass(), ENT_QUOTES, 'UTF-8'); ?>">
                                    <?= htmlspecialchars($candidature->getStatusEnum()->label(), ENT_QUOTES, 'UTF-8'); ?>
                                </span>

                                <?php if ($candidature->getCreatedAt() !== null): ?>
                                    <small class="text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?= htmlspecialchars($candidature->getCreatedAt()->format('d/m/Y'), ENT_QUOTES, 'UTF-8'); ?>
                                    </small>
                                <?php endif; ?>
                            </div>

                            <h2 class="h5 mb-2">
                                <?= htmlspecialchars($candidature->getOfferTitle(), ENT_QUOTES, 'UTF-8'); ?>
                            </h2>

                            <p class="text-secondary mb-4">
                                <i class="bi bi-building me-2"></i>
                                <?= htmlspecialchars($candidature->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <a 
                                    href="/offres/<?= urlencode($candidature->getOfferSlug()); ?>" 
                                    class="btn btn-primary">
                                    Voir l'offre
                                </a>

                                <?php $cvUrl = $pdfUploader->getPublicUrl($candidature->getCv()); ?>
                                <?php if ($cvUrl !== null): ?>
                                    <a 
                                        href="<?= htmlspecialchars($cvUrl, ENT_QUOTES, 'UTF-8'); ?>" 
                                        class="btn btn-outline-secondary" 
                                        target="_blank" 
                                        rel="noopener noreferrer">
                                        Voir mon CV
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>