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
    <section class="mt-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h2 class="h4 mb-1">Candidater à cette offre</h2>
                        <p class="text-secondary mb-0">
                            Envoyez votre CV et votre lettre de motivation.
                        </p>
                    </div>
                    <?php if (!empty($hasApplied)): ?>
                        <span class="badge text-bg-success fs-6">
                            Déjà postulé
                        </span>
                    <?php endif; ?>
                </div>
                <?php if (!$this->isAuthenticated()): ?>
                    <div class="alert alert-info mb-3">
                        Vous devez être connecté pour postuler à cette offre.
                    </div>
                    <a href="/login?redirect=<?= urlencode('/offres/' . $offer->getSlug()); ?>" class="btn btn-primary">
                        Connectez-vous pour postuler
                    </a>
                <?php elseif (!empty($hasApplied)): ?>
                    <div class="alert alert-success mb-0">
                        Votre candidature a déjà été envoyée pour cette offre.
                    </div>
                <?php else: ?>
                    <form method="post" enctype="multipart/form-data" action="/postuler/<?= (int) $offer->getIdOffer(); ?>">
                        <input
                            type="hidden"
                            name="_token"
                            value="<?= htmlspecialchars(\App\Core\Csrf::token('apply_' . $offer->getIdOffer()), ENT_QUOTES, 'UTF-8'); ?>">
                        <input
                            type="hidden"
                            name="slug"
                            value="<?= htmlspecialchars($offer->getSlug(), ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="mb-3">
                            <label for="cv" class="form-label">CV (PDF)</label>
                            <input
                                type="file"
                                name="cv"
                                id="cv"
                                class="form-control"
                                accept=".pdf,application/pdf"
                                required>
                            <div class="form-text">
                                Format accepté : PDF uniquement. Taille maximale : 2 Mo.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Lettre de motivation</label>
                            <textarea
                                name="cover_letter"
                                id="cover_letter"
                                class="form-control"
                                rows="6"
                                placeholder="Présentez en quelques lignes votre motivation pour cette offre..."
                                required><?= htmlspecialchars($oldApplication['cover_letter'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <button class="btn btn-primary">
                            Envoyer ma candidature
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>
</section>