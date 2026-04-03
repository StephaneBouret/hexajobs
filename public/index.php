<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';

use App\Core\Router;

// 1) On charge la liste des routes depuis config/routes.php
$router = new Router(require dirname(__DIR__) . '/config/routes.php');

// 2) On donne au router la méthode HTTP + l'URI demandée
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);