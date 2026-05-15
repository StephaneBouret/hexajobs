<?php

/** @var array<string,string> $errors */
/** @var array<string,string> $old */

$formAction = '/admin/categories/create';
$csrfTokenId = 'create_category';
$submitLabel = 'Créer la catégorie';
?>

<section class="py-4">
    <div class="mb-4">
        <a href="/admin/categories" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>
            Retour aux catégories
        </a>

        <h1 class="mb-1">Créer une catégorie</h1>

        <p class="text-secondary mb-0">
            Ajoutez une nouvelle catégorie d'offres.
        </p>
    </div>

    <?php require VIEW_PATH . '/admin_category/_form.php'; ?>
</section>