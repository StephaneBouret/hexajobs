<?php

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
                <li class="nav-item">
                    <a class="nav-link <?= Router::isActiveRoute('/') ? 'active' : '' ?>" href="/">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= Router::isActiveRoute('/offres') ? 'active' : '' ?>" href="/offres">
                        Offres
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= Router::isActiveRoute('/entreprises') ? 'active' : '' ?>" href="/entreprises">
                        Entreprises
                    </a>
                </li>
            </ul>
            <!-- Zone authentification -->
            <ul class="navbar-nav ms-auto">
                <?php if ($this->getUser()): ?>
                    <li class="nav-item d-flex align-items-center me-3">
                        <span class="navbar-text">
                            Bonjour <?= htmlspecialchars($this->getUser()['firstname']) ?>
                        </span>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <form method="post" action="/logout">
                            <input type="hidden" name="_token" value="<?= \App\Core\Csrf::token('logout') ?>">
                            <button class="btn btn-sm btn-outline-danger">
                                Déconnexion
                            </button>
                        </form>
                    </li>
                <?php else: ?>
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
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>