<?php

/** @var \App\Entities\Offer[] $offers */
/** @var int $totalOffers */
/** @var array{q:string, location:string, contracts:array} $criteria */
/** @var \App\Enum\ContractType[] $contractTypes */
/** @var array $pagination */
?>

<section class="py-4">
    <div class="mb-4">
        <h1 class="mb-2">
            <?= (int) $totalOffers; ?>
            offre<?= $totalOffers > 1 ? 's' : ''; ?>
        </h1>
        <p class="text-secondary mb-0">
            Résultats de recherche sur HexaJobs.
        </p>
    </div>

    <form method="get" action="/offres" class="card border-0 shadow-sm rounded-4 mb-4" data-offer-search-form>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5 position-relative">
                    <label class="form-label fw-bold text-uppercase small">Quoi ?</label>
                    <input
                        type="search"
                        name="q"
                        class="form-control"
                        value="<?= htmlspecialchars($criteria['q'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Métier, titre..."
                        autocomplete="off"
                        data-autocomplete-input
                        data-autocomplete-url="/api/offres/suggestions/titres"
                        data-autocomplete-target="#offer-title-suggestions">

                    <div
                        id="offer-title-suggestions"
                        class="list-group position-absolute w-100 shadow-sm d-none"
                        style="z-index: 1000;"
                        data-autocomplete-results></div>
                </div>

                <div class="col-lg-5 position-relative">
                    <label class="form-label fw-bold text-uppercase small">Où ?</label>
                    <input
                        type="search"
                        name="location"
                        class="form-control"
                        value="<?= htmlspecialchars($criteria['location'], ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Ville..."
                        autocomplete="off"
                        data-autocomplete-input
                        data-autocomplete-url="/api/offres/suggestions/villes"
                        data-autocomplete-target="#offer-location-suggestions">

                    <div
                        id="offer-location-suggestions"
                        class="list-group position-absolute w-100 shadow-sm d-none"
                        style="z-index: 1000;"
                        data-autocomplete-results></div>
                </div>

                <div class="col-lg-2">
                    <button class="btn btn-dark w-100">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <?php foreach ($contractTypes as $contract): ?>
                    <?php $checked = in_array($contract->value, $criteria['contracts'], true); ?>

                    <input
                        type="checkbox"
                        class="btn-check"
                        id="contract-<?= htmlspecialchars($contract->name, ENT_QUOTES, 'UTF-8'); ?>"
                        name="contracts[]"
                        value="<?= htmlspecialchars($contract->value, ENT_QUOTES, 'UTF-8'); ?>"
                        data-contract-filter
                        <?= $checked ? 'checked' : ''; ?>>

                    <label
                        class="btn btn-outline-secondary"
                        for="contract-<?= htmlspecialchars($contract->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars($contract->label(), ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                <?php endforeach; ?>

                <?php if ($criteria['q'] !== '' || $criteria['location'] !== '' || !empty($criteria['contracts'])): ?>
                    <a href="/offres" class="btn btn-link text-decoration-none">
                        Réinitialiser
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

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

        <?php require VIEW_PATH . '/partials/_pagination.php'; ?>
    <?php endif; ?>
</section>