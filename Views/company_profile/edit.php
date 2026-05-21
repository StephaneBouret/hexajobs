<?php

$old = $old ?? [
    'company_name' => '',
    'siret' => '',
    'address' => '',
    'postal_code' => '',
    'city' => '',
    'url' => '',
    'description' => '',
];
$errors = $errors ?? [];
$pageTitle = $pageTitle ?? 'Modifier mon entreprise';
?>

<h1 class="mb-4"><?= htmlspecialchars($pageTitle ?? 'Modifier mon entreprise', ENT_QUOTES, 'UTF-8'); ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="/entreprise/profil">
    <input type="hidden" name="_token" value="<?= htmlspecialchars(\App\Core\Csrf::token('edit_company_profile'), ENT_QUOTES, 'UTF-8'); ?>">

    <div class="mb-3">
        <label class="form-label">Nom de l'entreprise</label>
        <input type="text" name="company_name" class="form-control" value="<?= $this->old($old, 'company_name'); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">SIRET</label>
        <input type="text" name="siret" class="form-control" value="<?= $this->old($old, 'siret'); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Adresse</label>
        <input type="text" name="address" class="form-control" value="<?= $this->old($old, 'address'); ?>" required>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Code postal</label>
            <input type="text" name="postal_code" class="form-control" value="<?= $this->old($old, 'postal_code'); ?>" required>
        </div>
        <div class="col-md-8 mb-3">
            <label class="form-label">Ville</label>
            <input type="text" name="city" class="form-control" value="<?= $this->old($old, 'city'); ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Site web</label>
        <input type="url" name="url" class="form-control" value="<?= $this->old($old, 'url'); ?>">
    </div>

    <div class="mb-4">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5" required><?= $this->old($old, 'description'); ?></textarea>
    </div>

    <button class="btn btn-primary">
        Enregistrer les modifications
    </button>

    <a href="/entreprise/offres" class="btn btn-outline-secondary">
        Annuler
    </a>
</form>
