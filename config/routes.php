<?php

declare(strict_types=1);

use App\Controllers\Admin\AdminCategoryController;
use App\Controllers\Admin\AdminCompanyController;
use App\Controllers\Admin\AdminOfferController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\AuthController;
use App\Controllers\CandidatureController;
use App\Controllers\CompanyAuthController;
use App\Controllers\CompanyCandidatureController;
use App\Controllers\CompanyController;
use App\Controllers\CompanyOfferController;
use App\Controllers\HomeController;
use App\Controllers\OfferController;

return [
    // Home
    ['GET', '/',                                                 [HomeController::class, 'index']],

    // Offres
    ['GET', '/offres',                                           [OfferController::class, 'index']],
    ['GET', '/offres/{slug}',                                    [OfferController::class, 'show']],

    // Entreprises
    ['GET', '/entreprises',                                      [CompanyController::class, 'index']],
    ['GET', '/entreprises/{slug}',                               [CompanyController::class, 'show']],

    // Auth
    ['GET',  '/register',                                        [AuthController::class, 'register']],
    ['POST', '/register',                                        [AuthController::class, 'register']],
    ['GET',  '/login',                                           [AuthController::class, 'login']],
    ['POST', '/login',                                           [AuthController::class, 'login']],
    ['POST', '/logout',                                          [AuthController::class, 'logout'], '@AUTH'],

    // Candidatures
    ['GET',  '/candidatures',                                    [CandidatureController::class, 'index'], 'ROLE_USER'],
    ['POST', '/postuler/{idOffer}',                              [CandidatureController::class, 'apply'], 'ROLE_USER'],

    // Espace entreprise
    ['GET',  '/entreprise/login',                                [CompanyAuthController::class, 'login']],
    ['POST', '/entreprise/login',                                [CompanyAuthController::class, 'login']],

    ['GET',  '/entreprise/register',                             [CompanyAuthController::class, 'register']],
    ['POST', '/entreprise/register',                             [CompanyAuthController::class, 'register']],

    ['GET',  '/entreprise/offres',                               [CompanyOfferController::class, 'index'], 'ROLE_COMPANY'],

    ['GET',  '/entreprise/offres/create',                        [CompanyOfferController::class, 'create'], 'ROLE_COMPANY'],
    ['POST', '/entreprise/offres/create',                        [CompanyOfferController::class, 'create'], 'ROLE_COMPANY'],

    ['GET', '/entreprise/offres/{idOffer}',                      [CompanyOfferController::class, 'showById'], 'ROLE_COMPANY'],
    ['GET',  '/entreprise/offres/{idOffer}/edit',                [CompanyOfferController::class, 'edit'],   'ROLE_COMPANY'],
    ['POST', '/entreprise/offres/{idOffer}/edit',                [CompanyOfferController::class, 'edit'],   'ROLE_COMPANY'],
    ['POST', '/entreprise/offres/{idOffer}/delete',              [CompanyOfferController::class, 'delete'], 'ROLE_COMPANY'],

    ['GET', '/entreprise/offres/{idOffer}/candidatures',         [CompanyCandidatureController::class, 'index'], 'ROLE_COMPANY'],
    ['GET', '/entreprise/candidatures/{idCandidature}',          [CompanyCandidatureController::class, 'show'], 'ROLE_COMPANY'],
    ['POST', '/entreprise/candidatures/{idCandidature}/retenir', [CompanyCandidatureController::class, 'accept'], 'ROLE_COMPANY'],
    ['POST', '/entreprise/candidatures/{idCandidature}/refuser', [CompanyCandidatureController::class, 'reject'], 'ROLE_COMPANY'],

    // Admin
    ['GET', '/admin',                                            [DashboardController::class, 'index'], 'ROLE_ADMIN'],
    ['GET', '/admin/users',                                      [AdminUserController::class, 'index'], 'ROLE_ADMIN'],
    ['GET', '/admin/companies',                                  [AdminCompanyController::class, 'index'], 'ROLE_ADMIN'],
    ['GET', '/admin/offers',                                     [AdminOfferController::class, 'index'], 'ROLE_ADMIN'],
    ['POST', '/admin/offers/{idOffer}/disable',                  [AdminOfferController::class, 'disable'], 'ROLE_ADMIN'],
    ['POST', '/admin/offers/{idOffer}/enable',                   [AdminOfferController::class, 'enable'],  'ROLE_ADMIN'],
    ['GET',  '/admin/categories',                                [AdminCategoryController::class, 'index'],  'ROLE_ADMIN'],
    ['GET',  '/admin/categories/create',                         [AdminCategoryController::class, 'create'],  'ROLE_ADMIN'],
    ['POST', '/admin/categories/create',                         [AdminCategoryController::class, 'create'],  'ROLE_ADMIN'],
    ['GET',  '/admin/categories/{idCategory}/edit',              [AdminCategoryController::class, 'edit'],  'ROLE_ADMIN'],
    ['POST', '/admin/categories/{idCategory}/edit',              [AdminCategoryController::class, 'edit'],  'ROLE_ADMIN'],
    ['POST', '/admin/categories/{idCategory}/delete',            [AdminCategoryController::class, 'delete'],  'ROLE_ADMIN'],

    // Search
    ['GET', '/api/offres/suggestions/titres',                    [OfferController::class, 'suggestTitles']],
    ['GET', '/api/offres/suggestions/villes',                    [OfferController::class, 'suggestLocations']],
];
