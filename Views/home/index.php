<section class="py-4 py-lg-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge text-bg-primary mb-3">HexaJobs</span>
            <h1 class="display-5 fw-bold mb-3">Trouvez votre prochain job, partout en France</h1>
            <p class="lead text-secondary mb-4">
                Consultez les dernières offres d'emploi, découvrez les entreprises qui recrutent
                et postulez aux annonces qui vous correspondent.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="/offres" class="btn btn-primary btn-lg">
                    Voir toutes les offres
                </a>
                <a href="/entreprises" class="btn btn-outline-primary btn-lg">
                    Découvrir les entreprises
                </a>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3">Pourquoi HexaJobs ?</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Offres récentes et lisibles
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Entreprises mises en avant
                        </li>
                        <li>
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Plateforme claire, pensée MVC
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Les 5 dernières annonces</h2>
            <p class="text-secondary mb-0">Les offres les plus récentes publiées sur la plateforme.</p>
        </div>
        <a href="/offres" class="btn btn-outline-primary">Toutes les offres</a>
    </div>

    <?php if (empty($latestOffers)): ?>
        <div class="alert alert-info mb-0">
            Aucune annonce n'est disponible pour le moment.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($latestOffers as $offer): ?>
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

                            <h3 class="h5 card-title mb-2">
                                <?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
                            </h3>

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

                            <p class="card-text text-secondary mb-4">
                                <?= htmlspecialchars(mb_strimwidth($offer->getDescription(), 0, 140, '...'), ENT_QUOTES, 'UTF-8'); ?>
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

<section class="py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Les entreprises qui recrutent le plus</h2>
            <p class="text-secondary mb-0">Les employeurs les plus actifs du moment.</p>
        </div>
    </div>

    <?php if (empty($topCompanies)): ?>
        <div class="alert alert-info">
            Aucune entreprise n'est mise en avant pour le moment.
        </div>
    <?php else: ?>
        <div id="topCompaniesCarousel" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
            <div class="carousel-inner">
                <?php foreach ($topCompanies as $index => $company): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-body p-4 p-lg-5">
                                <div class="row align-items-center g-4">
                                    <div class="col-lg-8">
                                        <span class="badge text-bg-primary mb-3">
                                            <?= (int) $company->getOffersCount(); ?> offre<?= $company->getOffersCount() > 1 ? 's' : ''; ?> active<?= $company->getOffersCount() > 1 ? 's' : ''; ?>
                                        </span>

                                        <h3 class="h2 mb-3">
                                            <?= htmlspecialchars($company->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                        </h3>

                                        <p class="text-secondary mb-2">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            <?= htmlspecialchars($company->getCity(), ENT_QUOTES, 'UTF-8'); ?>
                                        </p>

                                        <p class="text-secondary mb-4">
                                            <?= htmlspecialchars($company->getShortDescription(180), ENT_QUOTES, 'UTF-8'); ?>
                                        </p>

                                        <a href="/entreprises/<?= urlencode($company->getSlug()); ?>" class="btn btn-primary">
                                            Voir l'entreprise
                                        </a>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="bg-light rounded-4 p-4 text-center">
                                            <i class="bi bi-buildings fs-1 text-primary"></i>
                                            <p class="mt-3 mb-0 fw-semibold">
                                                Recrute actuellement sur HexaJobs
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($topCompanies) > 1): ?>
                <div class="carousel-indicators position-static mt-4 mb-0">
                    <?php foreach ($topCompanies as $index => $company): ?>
                        <button
                            type="button"
                            data-bs-target="#topCompaniesCarousel"
                            data-bs-slide-to="<?= $index; ?>"
                            class="<?= $index === 0 ? 'active' : ''; ?>"
                            <?= $index === 0 ? 'aria-current="true"' : ''; ?>
                            aria-label="Slide <?= $index + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
            <a href="/entreprises" class="btn btn-outline-primary btn-lg">
                Toutes les entreprises
            </a>
        </div>
    <?php endif; ?>
</section>