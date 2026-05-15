<?php

/** @var \App\Entities\User[] $users */
?>
<section class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Utilisateurs</h1>
            <p class="text-secondary mb-0">
                Consultez les comptes enregistrés sur la plateforme.
            </p>
        </div>

        <a href="/admin" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Utilisateur</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Entreprise</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">
                                    Aucun utilisateur.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($user->getFullName(), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="small text-secondary">
                                        #<?= (int) $user->getIdUser(); ?>
                                    </div>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>

                                <td>
                                    <?php
                                    $role = $user->getRole();
                                    $badgeClass = match ($role) {
                                        'ROLE_ADMIN' => 'text-bg-danger',
                                        'ROLE_COMPANY' => 'text-bg-primary',
                                        default => 'text-bg-secondary',
                                    };
                                    ?>

                                    <span class="badge <?= $badgeClass; ?>">
                                        <?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($user->getCompanyName() !== ''): ?>
                                        <?= htmlspecialchars($user->getCompanyName(), ENT_QUOTES, 'UTF-8'); ?>
                                    <?php else: ?>
                                        <span class="text-muted small">Aucune</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($user->getCreatedAt() !== null): ?>
                                        <span class="small text-secondary">
                                            <?= htmlspecialchars($user->getCreatedAt()->format('d/m/Y'), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Non renseignée</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>