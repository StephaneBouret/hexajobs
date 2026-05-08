<?php 
/** @var \App\Entities\Offer $offer */
?>
<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a href="/entreprise/offres/<?= (int) $offer->getIdOffer(); ?>" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left me-2">Retour à l'offre</i>
            </a>

            <h1 class="mb-1">Candidatures reçues</h1>
            <p class="text-secondary mb-0">
                Offre : <?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
            </p>
        </div>
    </div>

    <?php if (empty($candidatures)): ?>
        <div class="alert alert-info border-0 shadow-sm rounded-4">
            Aucune candidature reçue pour cette offre.
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Candidat</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($candidatures as $candidature): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        <?= htmlspecialchars($candidature->getUserFullName(), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($candidature->getUserEmail(), ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-<?= htmlspecialchars($candidature->getStatusEnum()->badgeClass(), ENT_QUOTES, 'UTF-8'); ?>">
                                            <?= htmlspecialchars($candidature->getStatusEnum()->label(), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($candidature->getCreatedAt() !== null): ?>
                                            <span class="small text-secondary">
                                                <?= htmlspecialchars($candidature->getCreatedAt()->format('d/m/Y'), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a 
                                            href="/entreprise/candidatures/<?= (int) $candidature->getIdCandidature(); ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            Ouvrir
                                        </a>
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