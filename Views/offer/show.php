<section class="py-4">
    <div class="mb-4">
        <a href="/offres" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>Retour aux offres
        </a>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge text-bg-light border">
                        <?= htmlspecialchars($offer->getCategoryName(), ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <span class="badge text-bg-primary">
                        <?= htmlspecialchars($offer->getContract(), ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>

                <h1 class="mb-3"><?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h1>

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

                <hr>

                <div class="mt-4">
                    <h2 class="h4 mb-3">Description du poste</h2>
                    <p class="mb-0" style="white-space: pre-line;">
                        <?= htmlspecialchars($offer->getDescription(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>