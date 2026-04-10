<section class="py-4">
    <a href="/entreprises" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-2"></i>Retour aux entreprises
    </a>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <h1 class="mb-2"><?= htmlspecialchars($company->getName(), ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="text-secondary mb-1">
                        <i class="bi bi-geo-alt me-2"></i>
                        <?= htmlspecialchars($company->getAddress(), ENT_QUOTES, 'UTF-8'); ?>,
                        <?= htmlspecialchars($company->getPostalCode(), ENT_QUOTES, 'UTF-8'); ?>
                        <?= htmlspecialchars($company->getCity(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>

                <span class="badge text-bg-primary fs-6">
                    <?= (int) $company->getOffersCount(); ?> offre<?= $company->getOffersCount() > 1 ? 's' : ''; ?> active<?= $company->getOffersCount() > 1 ? 's' : ''; ?>
                </span>
            </div>

            <p class="mb-0" style="white-space: pre-line;">
                <?= htmlspecialchars($company->getDescription(), ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <?php if ($company->getUrl() !== ''): ?>
                <div class="mt-4">
                    <a href="<?= htmlspecialchars($company->getUrl(), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                        Visiter le site web
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <section>
        <h2 class="h3 mb-4">Offres publiées par cette entreprise</h2>

        <?php if (empty($offers)): ?>
            <div class="alert alert-info mb-0">Cette entreprise n'a aucune offre active pour le moment.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($offers as $offer): ?>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <span class="badge text-bg-light border mb-3">
                                    <?= htmlspecialchars($offer->getCategoryName(), ENT_QUOTES, 'UTF-8'); ?>
                                </span>

                                <h3 class="h5 mb-2"><?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h3>

                                <p class="text-secondary mb-2">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <?= htmlspecialchars($offer->getLocation(), ENT_QUOTES, 'UTF-8'); ?>
                                </p>

                                <p class="text-secondary mb-3">
                                    <i class="bi bi-cash-stack me-2"></i>
                                    <?= htmlspecialchars($offer->getSalary(), ENT_QUOTES, 'UTF-8'); ?>
                                </p>

                                <a href="/offres/<?= urlencode($offer->getSlug()); ?>" class="btn btn-primary">
                                    Voir l'offre
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</section>