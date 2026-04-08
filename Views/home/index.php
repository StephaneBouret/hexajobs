<h1>Bienvenue sur HexaJobs</h1>

<section>
    <h2>Les 5 dernières annonces</h2>

    <?php if (empty($latestOffers)): ?>
        <p>Aucune annonce n'est disponible pour le moment.</p>
    <?php else: ?>
        <div class="offers-list">
            <?php foreach ($latestOffers as $offer): ?>
                <article class="offer-card">
                    <h3>
                        <?= htmlspecialchars($offer->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
                    </h3>

                    <p>
                        <strong>Entreprise :</strong>
                        <?= htmlspecialchars($offer->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Catégorie :</strong>
                        <?= htmlspecialchars($offer->getCategoryName(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Localisation :</strong>
                        <?= htmlspecialchars($offer->getLocation(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Contrat :</strong>
                        <?= htmlspecialchars($offer->getContract(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>

                    <p>
                        <strong>Salaire :</strong>
                        <?= htmlspecialchars($offer->getSalary(), ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>