<h1 class="mb-4"><?= htmlspecialchars($pageTitle ?? 'Inscription entreprise') ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="/entreprise/register">
    <input type="hidden" name="_token" value="<?= htmlspecialchars(\App\Core\Csrf::token('company_register')) ?>">

    <h2 class="h5 mb-3">Entreprise</h2>

    <div class="mb-3">
        <label class="form-label">Nom de l'entreprise</label>
        <input type="text" name="company_name" class="form-control" value="<?= $this->old($old, 'company_name') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">SIRET</label>
        <input type="text" name="siret" class="form-control" value="<?= $this->old($old, 'siret') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Adresse</label>
        <input type="text" name="address" class="form-control" value="<?= $this->old($old, 'address') ?>" required>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Code postal</label>
            <input type="text" name="postal_code" class="form-control" value="<?= $this->old($old, 'postal_code') ?>" required>
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control" value="<?= $this->old($old, 'city') ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Site web</label>
        <input type="url" name="url" class="form-control" value="<?= $this->old($old, 'url') ?>">
    </div>

    <div class="mb-4">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5" required><?= $this->old($old, 'description') ?></textarea>
    </div>

    <h2 class="h5 mb-3">Contact entreprise</h2>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="firstname" class="form-control" value="<?= $this->old($old, 'firstname') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="lastname" class="form-control" value="<?= $this->old($old, 'lastname') ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email professionnel</label>
        <input type="email" name="email" class="form-control" value="<?= $this->old($old, 'email') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-4">
        <label class="form-label">Confirmation du mot de passe</label>
        <input type="password" name="password_confirm" class="form-control" required>
    </div>

    <button class="btn btn-success">
        Créer mon compte entreprise
    </button>
</form>

<p class="mt-3">
    Déjà un compte entreprise ?
    <a href="/entreprise/login">Se connecter</a>
</p>