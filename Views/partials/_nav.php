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
        </div>
    </div>
</nav>