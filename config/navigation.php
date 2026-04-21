<?php

return [
    [
        'label' => 'Accueil',
        'url' => '/',
        'icon' => 'bi-house',
        'roles' => null, // visible pour tous
    ],
    [
        'label' => 'Offres',
        'url' => '/offres',
        'icon' => 'bi-briefcase',
        'roles' => null,
    ],
    [
        'label' => 'Entreprises',
        'url' => '/entreprises',
        'icon' => 'bi-buildings',
        'roles' => null,
    ],

    // USER
    [
        'label' => 'Mes candidatures',
        'url' => '/candidatures',
        'icon' => 'bi-file-earmark-text',
        'roles' => ['ROLE_USER'],
    ],

    // COMPANY
    [
        'label' => 'Espace entreprise',
        'url' => '/entreprise/offres',
        'icon' => 'bi-briefcase-fill',
        'roles' => ['ROLE_COMPANY'],
    ],

    // ADMIN
    [
        'label' => 'Dashboard',
        'url' => '/admin',
        'icon' => 'bi-speedometer2',
        'roles' => ['ROLE_ADMIN'],
    ],
];
