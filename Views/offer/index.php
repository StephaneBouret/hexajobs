<section class="py-4">
    <div class="mb-4">
        <h1 class="mb-2">Toutes les offres</h1>
        <p class="text-secondary mb-0">Retrouvez l'ensemble des offres actuellement publiées.</p>
    </div>

    <?php if (empty($offers)): ?>
        <div class="alert alert-info">Aucune offre n'est disponible.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($offers as $offer): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <span class="badge text-bg-light border">
                                    <?= htmlspecialchars($offer->getCategoryName(), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span class="badge text-bg-primary">
                                    <?= htmlspecialchars($offer->getContract(), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>

                            <h2 class="h5 mb-2"><?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h2>

                            <p class="text-secondary mb-2">
                                <i class="bi bi-building me-2"></i>
                                <?= htmlspecialchars($offer->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <p class="text-secondary mb-2">
                                <i class="bi bi-geo-alt me-2"></i>
                                <?= htmlspecialchars($offer->getLocation(), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <p class="text-secondary mb-3">
                                <i class="bi bi-cash-stack me-2"></i>
                                <?= htmlspecialchars($offer->getSalary(), ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <div class="mt-auto">
                                <a href="/offres/<?= urlencode($offer->getSlug()); ?>" class="btn btn-primary w-100">
                                    Voir l'offre
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>