<?php

/** @var \App\Entities\Category $category */
/** @var array<string,string> $errors */
/** @var array<string,string> $old */

$formAction = '/admin/categories/' . $category->getIdCategory() . '/edit';
$csrfTokenId = 'edit_category_' . $category->getIdCategory();
$submitLabel = 'Enregistrer les modifications';
?>

<section class="py-4">
    <div class="mb-4">
        <a href="/admin/categories" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>
            Retour aux catégories
        </a>

        <h1 class="mb-1">Modifier une catégorie</h1>

        <p class="text-secondary mb-0">
            Modifiez les informations de la catégorie.
        </p>
    </div>

    <?php require VIEW_PATH . '/admin_category/_form.php'; ?>
</section>