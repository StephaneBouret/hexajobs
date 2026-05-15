<?php

/** @var \App\Entities\Category[] $categories */
?>

<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Catégories</h1>
            <p class="text-secondary mb-0">
                Gérez les catégories des offres d'emploi.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="/admin" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Dashboard
            </a>

            <a href="/admin/categories/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Créer une catégorie
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nom</th>
                            <th>Slug</th>
                            <th>Offres liées</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">
                                    Aucune catégorie.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <?= htmlspecialchars($category->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <code>
                                        <?= htmlspecialchars($category->getSlug(), ENT_QUOTES, 'UTF-8'); ?>
                                    </code>
                                </td>

                                <td>
                                    <?php $count = $category->getOffersCount(); ?>

                                    <span class="badge <?= $count > 0
                                                            ? 'text-bg-primary'
                                                            : 'text-bg-light border text-muted'; ?>">

                                        <i class="bi bi-briefcase me-1"></i>

                                        <?= $count > 0 ? (int) $count : 'Aucune'; ?>
                                    </span>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <a
                                            href="/admin/categories/<?= (int) $category->getIdCategory(); ?>/edit"
                                            class="btn btn-sm btn-outline-primary">
                                            Modifier
                                        </a>

                                        <form
                                            method="post"
                                            action="/admin/categories/<?= (int) $category->getIdCategory(); ?>/delete"
                                            onsubmit="return confirm('Supprimer cette catégorie ?');"
                                            class="d-inline">

                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="<?= htmlspecialchars(\App\Core\Csrf::token('delete_category_' . $category->getIdCategory()), ENT_QUOTES, 'UTF-8'); ?>">

                                            <button
                                                class="btn btn-sm btn-outline-danger"
                                                <?= $count > 0 ? 'disabled' : ''; ?>>
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
</section>