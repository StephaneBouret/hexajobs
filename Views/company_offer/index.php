<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Mes offres</h1>
            <p class="text-secondary mb-0">
                Gérez les annonces publiées par votre entreprise.
            </p>
        </div>

        <a href="/entreprise/offres/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Créer une offre
        </a>
    </div>

    <?php if (empty($offers)): ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-briefcase fs-1 text-secondary"></i>
                </div>

                <h2 class="h5">Aucune offre publiée</h2>
                <p class="text-secondary mb-4">
                    Vous n'avez pas encore créé d'offre d'emploi.
                </p>

                <a href="/entreprise/offres/create" class="btn btn-primary">
                    Publier ma première offre
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Offre</th>
                                <th>Catégorie</th>
                                <th>Contrat</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($offers as $offer): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">
                                            <?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div class="small text-secondary">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?= htmlspecialchars($offer->getLocation(), ENT_QUOTES, 'UTF-8'); ?>
                                            ·
                                            <?= htmlspecialchars($offer->getSalary(), ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
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
                                        <?php if ($offer->getCreatedAt() !== null): ?>
                                            <span class="small text-secondary">
                                                <?= htmlspecialchars($offer->getCreatedAt()->format('d/m/Y'), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a
                                                href="/entreprise/offres/<?= (int) $offer->getIdOffer(); ?>"
                                                class="btn btn-outline-secondary">
                                                Voir
                                            </a>

                                            <a
                                                href="/entreprise/offres/<?= (int) $offer->getIdOffer(); ?>/edit"
                                                class="btn btn-outline-primary">
                                                Modifier
                                            </a>

                                            <form
                                                method="post"
                                                action="/entreprise/offres/<?= (int) $offer->getIdOffer(); ?>/delete"
                                                onsubmit="return confirm('Supprimer cette offre ?');"
                                                class="d-inline">
                                                <input
                                                    type="hidden"
                                                    name="_token"
                                                    value="<?= htmlspecialchars(\App\Core\Csrf::token('delete_company_offer_' . $offer->getIdOffer()), ENT_QUOTES, 'UTF-8'); ?>">

                                                <button class="btn btn-outline-danger">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>