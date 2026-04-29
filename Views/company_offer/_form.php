<?php

use App\Core\Csrf;
use App\Enum\ContractType;

$old = $old ?? [
    'title' => '',
    'description' => '',
    'location' => '',
    'contract' => 'CDI',
    'salary' => '',
    'status' => 'active',
    'id_category' => '',
];
$categories = $categories ?? [];
$errors = $errors ?? [];
?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <input
        type="hidden"
        name="_token"
        value="<?= htmlspecialchars(Csrf::token('create_company_offer'), ENT_QUOTES, 'UTF-8'); ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Titre de l'offre</label>
        <input
            type="text"
            id="title"
            name="title"
            class="form-control"
            value="<?= $this->old($old, 'title'); ?>"
            required>
    </div>

    <div class="mb-3">
        <label for="id_category" class="form-label">Catégorie</label>
        <select id="id_category" name="id_category" class="form-select" required>
            <option value="">Choisir une catégorie</option>

            <?php foreach ($categories as $category): ?>
                <option
                    value="<?= (int) $category->getIdCategory(); ?>"
                    <?= (string) $category->getIdCategory() === ($old['id_category'] ?? '') ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($category->getName(), ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description du poste</label>
        <textarea
            id="description"
            name="description"
            class="form-control"
            rows="7"
            required><?= $this->old($old, 'description'); ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="location" class="form-label">Localisation</label>
            <input
                type="text"
                id="location"
                name="location"
                class="form-control"
                value="<?= $this->old($old, 'location'); ?>"
                required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="contract" class="form-label">Type de contrat</label>
            <select id="contract" name="contract" class="form-select" required>
                <?php foreach (ContractType::cases() as $contract): ?>
                    <option
                        value="<?= htmlspecialchars($contract->value, ENT_QUOTES, 'UTF-8'); ?>"
                        <?= ($old['contract'] ?? '') === $contract->value ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($contract->value, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="salary" class="form-label">Rémunération</label>
            <input
                type="text"
                id="salary"
                name="salary"
                class="form-control"
                placeholder="Ex : 35 000 - 42 000 €"
                value="<?= $this->old($old, 'salary'); ?>"
                required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Statut</label>
            <select id="status" name="status" class="form-select">
                <option value="active" <?= ($old['status'] ?? '') === 'active' ? 'selected' : ''; ?>>
                    Active
                </option>
                <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>
                    Inactive
                </option>
            </select>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-primary">
            Publier l'offre
        </button>

        <a href="/entreprise/offres" class="btn btn-outline-secondary">
            Annuler
        </a>
    </div>
</form>