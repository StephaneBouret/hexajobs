<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\CompanyController;
use App\Controllers\HomeController;
use App\Controllers\OfferController;

return [
    // Home
    ['GET', '/',                          [HomeController::class, 'index']],

    // Offres
    ['GET', '/offres',                    [OfferController::class, 'index']],
    ['GET', '/offres/{slug}',             [OfferController::class, 'show']],

    // Entreprises
    ['GET', '/entreprises',               [CompanyController::class, 'index']],
    ['GET', '/entreprises/{slug}',        [CompanyController::class, 'show']],

    // Auth
    ['GET',  '/register',                 [AuthController::class, 'register']],
    ['POST', '/register',                 [AuthController::class, 'register']],
    ['GET',  '/login',                    [AuthController::class, 'login']],
    ['POST', '/login',                    [AuthController::class, 'login']],
    ['POST', '/logout',                   [AuthController::class, 'logout'], '@AUTH'],
];