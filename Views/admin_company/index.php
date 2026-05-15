<?php
/** @var \App\Entities\Company[] $companies */
?>

<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Entreprises</h1>
            <p class="text-secondary mb-0">
                Consultez les entreprises inscrites sur la plateforme.
            </p>
        </div>

        <a href="/admin" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Entreprise</th>
                            <th>Ville</th>
                            <th>SIRET</th>
                            <th>Offres</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($companies)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    Aucune entreprise.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($companies as $company): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($company->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="small text-secondary">
                                        #<?= (int) $company->getIdCompany(); ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($company->getCity(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($company->getSiret(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <span class="badge text-bg-primary">
                                        <?= (int) $company->getOffersCount(); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($company->getCreatedAt() !== null): ?>
                                        <span class="small text-secondary">
                                            <?= htmlspecialchars($company->getCreatedAt()->format('d/m/Y'), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Non renseignée</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end pe-4">
                                    <a
                                        href="/entreprises/<?= urlencode($company->getSlug()); ?>"
                                        class="btn btn-sm btn-outline-primary"
                                        target="_blank"
                                        rel="noopener noreferrer">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>