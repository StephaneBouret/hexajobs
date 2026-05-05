<section class="py-4">
    <div class="mb-4">
        <h1 class="mb-2">Publier une offre</h1>
        <p class="text-secondary mb-0">
            Créez une nouvelle annonce visible par les candidats.
        </p>
    </div>

    <?php
    $formAction = '/entreprise/offres/create';
    $csrfTokenId = 'create_company_offer';
    $submitLabel = 'Publier l\'offre';

    require VIEW_PATH . '/company_offer/_form.php';
    ?>
</section>