<?php

/** @var \App\Entities\Offer[] $offers */
?>

<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Offres</h1>
            <p class="text-secondary mb-0">
                Consultez les offres publiées sur HexaJobs.
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
                            <th class="ps-4">Offre</th>
                            <th>Entreprise</th>
                            <th>Catégorie</th>
                            <th>Contrat</th>
                            <th>Statut</th>
                            <th>Candidatures</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($offers)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-4">
                                    Aucune offre.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($offers as $offer): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="small text-secondary">
                                        <?= htmlspecialchars($offer->getLocation(), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($offer->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <span class="badge text-bg-light border">
                                        <?= htmlspecialchars($offer->getCategoryName(), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge text-bg-info">
                                        <?= htmlspecialchars($offer->getContractEnum()->label(), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($offer->getStatus() === 'active'): ?>
                                        <span class="badge text-bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php $count = $offer->getCandidaturesCount(); ?>

                                    <span class="badge <?= $count > 0 ? 'text-bg-primary' : 'text-bg-light border text-muted'; ?>">
                                        <i class="bi bi-people me-1"></i>
                                        <?= $count > 0 ? (int) $count : 'Aucune'; ?>
                                    </span>
                                </td>

                                <td class="text-end pe-4">
                                    <a
                                        href="/offres/<?= urlencode($offer->getSlug()); ?>"
                                        class="btn btn-sm btn-outline-primary"
                                        target="_blank"
                                        rel="noopener noreferrer">
                                        Voir
                                    </a>

                                    <?php if ($offer->getStatus() === 'active'): ?>
                                        <form method="post" action="/admin/offers/<?= (int) $offer->getIdOffer(); ?>/disable" class="d-inline">
                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="<?= htmlspecialchars(\App\Core\Csrf::token('disable_offer_' . $offer->getIdOffer()), ENT_QUOTES, 'UTF-8'); ?>">

                                            <button class="btn btn-sm btn-outline-warning">
                                                Désactiver
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="/admin/offers/<?= (int) $offer->getIdOffer(); ?>/enable" class="d-inline">
                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="<?= htmlspecialchars(\App\Core\Csrf::token('enable_offer_' . $offer->getIdOffer()), ENT_QUOTES, 'UTF-8'); ?>">

                                            <button class="btn btn-sm btn-outline-success">
                                                Réactiver
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>