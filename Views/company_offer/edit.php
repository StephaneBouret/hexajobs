<?php

/** @var \App\Entities\Offer $offer */
?>
<section class="py-4">
    <div class="mb-4">
        <a href="/entreprise/offres/<?= (int) $offer->getIdOffer(); ?>" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left me-2"></i>Retour à l'offre
        </a>

        <h1 class="mb-2">Modifier une offre</h1>
        <p class="text-secondary mb-0">
            Mettez à jour les informations de votre annonce.
        </p>
    </div>

    <?php
    $formAction = '/entreprise/offres/' . $offer->getIdOffer() . '/edit';
    $csrfTokenId = 'edit_company_offer_' . $offer->getIdOffer();
    $submitLabel = 'Enregistrer les modifications';
    
    require VIEW_PATH . '/company_offer/_form.php';
    ?>
</section>