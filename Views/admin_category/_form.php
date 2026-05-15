<?php

/** @var array<string,string> $errors */
/** @var array<string,string> $old */
/** @var string $formAction */
/** @var string $csrfTokenId */
/** @var string $submitLabel */
?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form
            method="post"
            action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>">

            <input
                type="hidden"
                name="_token"
                value="<?= htmlspecialchars(\App\Core\Csrf::token($csrfTokenId), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="mb-4">
                <label for="name" class="form-label">
                    Nom de la catégorie
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : ''; ?>"
                    value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    required>

                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary">
                    <?= htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8'); ?>
                </button>
            </div>
        </form>
    </div>
</div>