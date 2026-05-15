<?php

/** @var array{
 *     users:int,
 *     companies:int,
 *     offers:int,
 *     activeOffers:int,
 *     candidatures:int
 * } $stats */

/** @var \App\Entities\Offer[] $latestOffers */
/** @var \App\Entities\Company[] $latestCompanies */
?>

<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Dashboard admin</h1>
            <p class="text-secondary mb-0">
                Vue globale de la plateforme HexaJobs.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="/admin/users" class="btn btn-outline-primary">
                <i class="bi bi-people me-1"></i>
                Utilisateurs
            </a>

            <a href="/admin/companies" class="btn btn-outline-primary">
                <i class="bi bi-buildings me-1"></i>
                Entreprises
            </a>

            <a href="/admin/offers" class="btn btn-outline-primary">
                <i class="bi bi-briefcase me-1"></i>
                Offres
            </a>

            <a href="/admin/categories" class="btn btn-outline-primary">
                <i class="bi bi-tags me-1"></i>
                Catégories
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Utilisateurs</div>
                    <div class="fs-3 fw-bold"><?= (int) $stats['users']; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Entreprises</div>
                    <div class="fs-3 fw-bold"><?= (int) $stats['companies']; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Offres</div>
                    <div class="fs-3 fw-bold"><?= (int) $stats['offers']; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Offres actives</div>
                    <div class="fs-3 fw-bold"><?= (int) $stats['activeOffers']; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-secondary small">Candidatures</div>
                    <div class="fs-3 fw-bold"><?= (int) $stats['candidatures']; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h2 class="h5 mb-0">Dernières offres publiées</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Offre</th>
                                <th>Entreprise</th>
                                <th>Statut</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestOffers as $offer): ?>
                                <tr>
                                    <td class="ps-4">
                                        <?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($offer->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $offer->getStatus() === 'active' ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                                            <?= htmlspecialchars($offer->getStatus(), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="/offres/<?= urlencode($offer->getSlug()); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($latestOffers)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-secondary py-4">
                                        Aucune offre.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h2 class="h5 mb-0">Dernières entreprises</h2>
                </div>

                <div class="list-group list-group-flush">
                    <?php foreach ($latestCompanies as $company): ?>
                        <a href="/entreprises/<?= urlencode($company->getSlug()); ?>" class="list-group-item list-group-item-action px-4 py-3">
                            <div class="fw-semibold">
                                <?= htmlspecialchars($company->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <div class="small text-secondary">
                                <?= htmlspecialchars($company->getCity(), ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <?php if (empty($latestCompanies)): ?>
                        <div class="list-group-item px-4 py-3 text-secondary">
                            Aucune entreprise.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>