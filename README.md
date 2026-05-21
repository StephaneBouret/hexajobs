# HexaJobs

Application pédagogique de gestion d'offres d'emploi développée en PHP avec une architecture MVC maison.

Le projet permet de travailler les bases d'une application web structurée : routing, controllers, models, views, entités, authentification, rôles, sécurité, CRUD, recherche, pagination et séparation des responsabilités.

## Objectif

Ce projet a été réalisé dans le cadre d'un exercice pédagogique afin de comprendre et pratiquer :

- le pattern MVC en PHP
- l'organisation d'un projet avec Composer
- les requêtes préparées avec PDO
- l'authentification et la gestion des rôles
- la protection CSRF des formulaires
- la séparation entre espace public, espace candidat, espace entreprise et espace administrateur
- la mise en place de CRUD métier
- la recherche, l'autocomplétion et la pagination

## Fonctionnalités

### Espace public

- Page d'accueil
- Liste des offres actives
- Recherche d'offres par titre
- Recherche d'offres par ville
- Filtrage par type de contrat
- Pagination des offres
- Suggestions AJAX pour les titres et les villes
- Page détail d'une offre
- Liste des entreprises
- Page détail d'une entreprise avec ses offres actives

### Candidat

- Inscription et connexion
- Consultation des offres
- Candidature à une offre
- Envoi d'un CV
- Lettre de motivation
- Suivi de ses candidatures

### Entreprise

- Inscription et connexion entreprise
- Création d'offres
- Consultation de ses offres
- Modification de ses offres
- Suppression de ses offres
- Consultation des candidatures reçues
- Acceptation ou refus d'une candidature

### Administrateur

- Dashboard d'administration
- Gestion des utilisateurs
- Gestion des entreprises
- Gestion des offres
- Désactivation et réactivation des offres
- Gestion des catégories

## Stack technique

- PHP 8
- MySQL
- PDO
- Composer
- Architecture MVC maison
- Bootstrap 5
- Bootstrap Icons
- JavaScript modulaire
- Dotenv
- Cocur Slugify

## Installation

### Etape 1 : cloner le projet

```bash
git clone https://github.com/votre-compte/hexajobs.git
cd hexajobs
```

### Etape 2 Installer les dépendances

```bash
composer install
```

### Etape 3 : configurer l'environnement

Créer un fichier .env.local ou adapter le fichier .env selon votre configuration locale.

Exemple :
DB_HOST=localhost
DB_NAME=hexajobs
DB_USER=root
DB_PASSWORD=

### Etape 4 : créer la base de données

Importer les fichiers SQL présents dans le dossier sql.

```bash
sql/hexajobs.sql
sql/hexajobs_data.sql
```

Le premier fichier contient la structure de la base.
Le second contient les données de démonstration.

### Etape 5 : lancer le serveur

Depuis la racine du projet :

```bash
php -S localhost:8000 -t public
```

Puis ouvrir :

```text
http://localhost:8000
```

### Comptes de test

#### Administrateur

| Email                                     | Mot de passe |
| ----------------------------------------- | ------------ |
| [admin@site.fr](mailto:admin@site.fr)     | admin123     |

#### Candidat

| Email                                     | Mot de passe |
| ----------------------------------------- | ------------ |
| [user0@gmail.com](mailto:user0@gmail.com) | password     |

### Sécurité

Le projet met en place plusieurs protections importantes :

- mots de passe hashés avec password_hash()
- vérification des mots de passe avec password_verify()
- requêtes préparées avec PDO
- protection CSRF sur les formulaires sensibles
- contrôle d'accès par rôle
- séparation des espaces ROLE_USER, ROLE_COMPANY et ROLE_ADMIN
- vérification de propriété sur les ressources entreprise

Exemple pédagogique important :

Une entreprise ne doit pouvoir modifier que ses propres offres.  
On utilise donc l'identifiant de l'entreprise connectée via la session, et non un identifiant librement transmis par l'utilisateur.

### Structure du projet

```bash
hexajobs
├── config
│   ├── bootstrap.php
│   ├── navigation.php
│   └── routes.php
├── public
│   ├── assets
│   │   ├── css
│   │   ├── js
│   │   ├── pdf
│   │   └── vendor
│   └── index.php
├── sql
│   ├── hexajobs.sql
│   └── hexajobs_data.sql
├── src
│   ├── Controllers
│   ├── Core
│   ├── Entities
│   ├── Enum
│   ├── Models
│   ├── Security
│   └── Service
├── Views
│   ├── admin_category
│   ├── admin_company
│   ├── admin_offer
│   ├── admin_user
│   ├── auth
│   ├── candidature
│   ├── company
│   ├── company_auth
│   ├── company_candidature
│   ├── company_offer
│   ├── company_profile
│   ├── dashboard
│   ├── errors
│   ├── home
│   ├── layout
│   ├── offer
│   └── partials
├── vendor
├── composer.json
└── README.md
```

### Organisation MVC

#### Controllers

Les contrôleurs reçoivent la requête, vérifient les droits, appellent les modèles puis rendent les vues.

Exemples :

- OfferController
- CompanyOfferController
- CompanyCandidatureController
- AdminOfferController  

#### Models

Les modèles contiennent les requêtes SQL et la logique d'accès aux données.

Exemples :

- OfferModel
- CompanyModel
- CandidatureModel
- UserModel

#### Entities

Les entités représentent les objets métier manipulés par l'application.

Exemples :

- Offer
- Company
- User
- Candidature

#### Views

Les vues affichent les données transmises par les contrôleurs.

Elles ne doivent pas contenir de logique métier complexe.  
Lorsque le template devient trop long, on peut extraire des morceaux dans des partials.

Exemple :

```php
require VIEW_PATH . '/partials/_pagination.php';
```

### Points pédagogiques travaillés

#### Pagination

La pagination des offres repose sur :

- une méthode de recherche paginée dans OfferModel
- une méthode de comptage dans OfferModel
- un calcul de page courante dans OfferController
- un partial réutilisable côté vue

#### Recherche

La recherche combine plusieurs critères :

- mot-clé
- localisation
- types de contrat

Les critères sont conservés dans l'URL afin que la pagination garde le contexte de recherche.

#### Autocomplétion

Des routes API retournent des suggestions au format JSON :

```text
/api/offres/suggestions/titres
/api/offres/suggestions/villes
```

#### CRUD entreprise

Une entreprise peut gérer ses propres offres :

- créer une offre
- consulter ses offres
- modifier une offre
- supprimer une offre

La vérification importante est faite avec l'identifiant de l'entreprise connectée.

#### Administration

L'administrateur dispose d'un espace séparé permettant de gérer les données globales du site.

Exemple :

Une offre peut être désactivée ou réactivée par l'administrateur, sans que l'entreprise ait nécessairement accès à cette action.

### Améliorations possibles

- Factoriser les composants de formulaire
- Ajouter des tests unitaires
- Ajouter des tests fonctionnels
- Ajouter une API REST
- Ajouter des notifications pour les candidatures
- Ajouter une messagerie candidat / entreprise
- Ajouter une gestion avancée des rôles
- Ajouter un système de favoris pour les candidats

### Auteur

Projet pédagogique réalisé dans le cadre d'un apprentissage PHP MVC.