-- ============================================
-- SUPPRESSION DES TABLES
-- ============================================

-- On supprime d'abord les tables qui dépendent d'autres tables
DROP TABLE IF EXISTS candidature;
DROP TABLE IF EXISTS offer;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS company;

-- ============================================
-- TABLE : company
-- ============================================

-- Cette table représente l'entreprise en tant qu'entité métier.
-- Elle ne gère pas directement l'authentification.
CREATE TABLE company (
  -- Identifiant unique de l'entreprise
  id_company INT AUTO_INCREMENT PRIMARY KEY,

  -- Nom de l'entreprise
  name VARCHAR(255) NOT NULL,

  -- Slug utilisé dans les URL
  slug VARCHAR(255) NOT NULL UNIQUE,

  -- Adresse postale de l'entreprise
  address VARCHAR(255) NOT NULL,

  -- Code postal
  postal_code VARCHAR(50) NOT NULL,

  -- Ville
  city VARCHAR(50) NOT NULL,

  -- Site web de l'entreprise
  url VARCHAR(255) DEFAULT NULL,

  -- Description de l'entreprise
  description TEXT NOT NULL,

  -- Numéro de SIRET
  siret VARCHAR(255) NOT NULL UNIQUE,

  -- Date de création de l'enregistrement
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ============================================
-- TABLE : category
-- ============================================

-- Cette table contient les catégories d'offres.
CREATE TABLE category (
  -- Identifiant unique de la catégorie
  id_category INT AUTO_INCREMENT PRIMARY KEY,

  -- Nom de la catégorie
  name VARCHAR(255) NOT NULL UNIQUE,

  -- Slug utilisé dans les URL
  slug VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ============================================
-- TABLE : user
-- ============================================

-- Cette table représente tous les comptes connectés
-- candidat, administrateur ou utilisateur rattaché à une entreprise.
CREATE TABLE user (
  -- Identifiant unique de l'utilisateur
  id_user INT AUTO_INCREMENT PRIMARY KEY,

  -- Prénom
  firstname VARCHAR(255) NOT NULL,

  -- Nom
  lastname VARCHAR(255) NOT NULL,

  -- Adresse email de connexion
  email VARCHAR(255) NOT NULL UNIQUE,

  -- Mot de passe hashé
  password VARCHAR(255) NOT NULL,

  -- Rôle de l'utilisateur :
  -- ROLE_USER, ROLE_ADMIN, ROLE_COMPANY
  role VARCHAR(50) NOT NULL DEFAULT 'ROLE_USER',

  -- Date de création du compte
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  -- Référence éventuelle vers une entreprise
  -- NULL si l'utilisateur n'est rattaché à aucune entreprise
  id_company INT DEFAULT NULL,

  -- Clé étrangère vers company
  CONSTRAINT user_id_company_FK 
    FOREIGN KEY (id_company) REFERENCES company(id_company)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLE : offer
-- ============================================

-- Cette table représente les offres publiées par les entreprises.
CREATE TABLE offer (
  -- Identifiant unique de l'offre
  id_offer INT AUTO_INCREMENT PRIMARY KEY,

  -- Titre de l'offre
  title VARCHAR(255) NOT NULL,

  -- Slug utilisé dans les URL
  slug VARCHAR(255) NOT NULL UNIQUE,

  -- Description complète de l'offre
  description TEXT NOT NULL,

  -- Lieu du poste
  location VARCHAR(255) NOT NULL,

  -- Type de contrat : CDI, CDD, alternance, etc.
  contract VARCHAR(255) NOT NULL DEFAULT 'CDI',

  -- Salaire ou fourchette salariale
  salary VARCHAR(255) NOT NULL,

  -- Statut de l'offre : active, inactive, archivee...
  status VARCHAR(50) NOT NULL DEFAULT 'active',

  -- Date de création / publication
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  -- Référence vers la catégorie de l'offre
  id_category INT NOT NULL,

  -- Référence vers l'entreprise qui publie l'offre
  id_company INT NOT NULL,

  -- Clé étrangère vers category
  CONSTRAINT offer_id_category_FK 
    FOREIGN KEY (id_category) REFERENCES category(id_category)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  -- Clé étrangère vers company
  CONSTRAINT offer_id_company_FK
    FOREIGN KEY (id_company) REFERENCES company(id_company)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLE : candidature
-- ============================================

-- Cette table représente une candidature déposée par un utilisateur
-- sur une offre d'emploi.
CREATE TABLE candidature (
  -- Identifiant unique de la candidature
  id_candidature INT AUTO_INCREMENT PRIMARY KEY,

  -- Nom ou chemin du fichier CV
  cv VARCHAR(255) NOT NULL,

  -- Lettre de motivation
  cover_letter TEXT NOT NULL,

  -- Statut de la candidature :
  -- en_attente, consultee, retenue, refusee
  status VARCHAR(50) NOT NULL DEFAULT 'en_attente',

  -- Date de dépôt de la candidature
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  -- Référence vers l'offre concernée
  id_offer INT NOT NULL,

  -- Référence vers l'utilisateur ayant postulé
  id_user INT NOT NULL,

  -- Clé étrangère vers offer
  CONSTRAINT candidature_id_offer_FK
    FOREIGN KEY (id_offer) REFERENCES offer(id_offer)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  -- Clé étrangère vers user
  CONSTRAINT candidature_id_user_FK
    FOREIGN KEY (id_user) REFERENCES user(id_user)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  -- Contrainte métier :
  -- un utilisateur ne peut postuler qu'une seule fois à une même offre
  CONSTRAINT unique_candidature UNIQUE (id_user, id_offer)
) ENGINE=InnoDB;