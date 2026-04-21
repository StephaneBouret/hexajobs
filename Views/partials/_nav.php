<?php
$user = $this->getUser();
$navigation = $this->getNavigation();

use App\Core\Router;
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">Hexajobs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <!-- Menu principal -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php foreach ($navigation as $item): ?>
                    <?php
                    $roles = $item['roles'] ?? null;

                    $canShow = $roles === null
                        || ($user && in_array($user['role'], $roles, true));
                    ?>

                    <?php if ($canShow): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= Router::isActiveRoute($item['url']) ? 'active' : '' ?>" href="<?= $item['url']; ?>">
                                <?php if (!empty($item['icon'])): ?>
                                    <i class="bi <?= $item['icon'] ?> me-1"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <!-- Zone authentification -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if ($user === null): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= Router::isActiveRoute('/login') ? 'active' : '' ?>" href="/login">
                            Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= Router::isActiveRoute('/register') ? 'active' : '' ?>" href="/register">
                            Inscription
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="userDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text text-muted small">
                                    <?= htmlspecialchars($user['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <?php foreach ($navigation as $item): ?>
                                <?php
                                $roles = $item['roles'] ?? null;

                                $canShow = $roles !== null
                                    && $user
                                    && in_array($user['role'], $roles, true);
                                ?>
                                <?php if ($canShow): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= $item['url']; ?>">
                                            <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="post" action="/logout" class="px-3">
                                    <input type="hidden" name="_token" value="<?= \App\Core\Csrf::token('logout') ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>