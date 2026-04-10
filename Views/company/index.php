<section class="py-4">
    <div class="mb-4">
        <h1 class="mb-2">Toutes les entreprises</h1>
        <p class="text-secondary mb-0">Découvrez les entreprises présentes sur HexaJobs.</p>
    </div>

    <?php if (empty($companies)): ?>
        <div class="alert alert-info">Aucune entreprise n'est disponible.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($companies as $company): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="h5 mb-0">
                                    <?= htmlspecialchars($company->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                </h2>
                                <span class="badge text-bg-primary">
                                    <?= (int) $company->getOffersCount(); ?> offre<?= $company->getOffersCount() > 1 ? 's' : ''; ?>
                                </span>
                            </div>

                            <p class="text-secondary mb-2">
                                <i class="bi bi-geo-alt me-2"></i>
                                <?= htmlspecialchars($company->getCity(), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <p class="text-secondary mb-4">
                                <?= htmlspecialchars($company->getShortDescription(), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <div class="mt-auto">
                                <a href="/entreprises/<?= urlencode($company->getSlug()); ?>" class="btn btn-outline-primary w-100">
                                    Voir l'entreprise
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>