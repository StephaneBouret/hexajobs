-- ============================================
-- INSERTION DES CATÉGORIES
-- ============================================

INSERT INTO category (name, slug) VALUES
('Développement web', 'developpement-web'),
('Design UI/UX', 'design-ui-ux'),
('Marketing digital', 'marketing-digital'),
('Ressources humaines', 'ressources-humaines'),
('Support informatique', 'support-informatique');

-- ============================================
-- INSERTION DES ENTREPRISES
-- ============================================

INSERT INTO company (name, slug, address, postal_code, city, url, description, siret) VALUES
(
    'TechNova',
    'technova',
    '12 rue des Lilas',
    '75010',
    'Paris',
    'https://www.technova.fr',
    'TechNova est une entreprise spécialisée dans le développement de solutions web sur mesure pour les PME et les startups.',
    '12345678901234'
),
(
    'BluePixel Studio',
    'bluepixel-studio',
    '8 avenue de la République',
    '69002',
    'Lyon',
    'https://www.bluepixel-studio.fr',
    'BluePixel Studio accompagne ses clients dans la création d''interfaces modernes et d''expériences utilisateurs efficaces.',
    '23456789012345'
),
(
    'MarketLead',
    'marketlead',
    '25 boulevard Voltaire',
    '33000',
    'Bordeaux',
    'https://www.marketlead.fr',
    'MarketLead est une agence spécialisée en acquisition digitale, référencement naturel et campagnes sponsorisées.',
    '34567890123456'
),
(
    'RH Horizon',
    'rh-horizon',
    '3 rue de la Paix',
    '44000',
    'Nantes',
    'https://www.rhhorizon.fr',
    'RH Horizon accompagne les entreprises dans leurs recrutements, la gestion des talents et le développement RH.',
    '45678901234567'
),
(
    'InfraSys',
    'infrasys',
    '18 place du Commerce',
    '59000',
    'Lille',
    'https://www.infrasys.fr',
    'InfraSys intervient dans le déploiement, la maintenance et le support des infrastructures informatiques.',
    '56789012345678'
);

-- ============================================
-- INSERTION DES OFFRES
-- ============================================

INSERT INTO offer (title, slug, description, location, contract, salary, status, id_category, id_company) VALUES
(
    'Développeur PHP MVC Junior',
    'developpeur-php-mvc-junior',
    'Vous participerez au développement et à la maintenance d''applications web en PHP orienté objet selon une architecture MVC.',
    'Paris',
    'CDI',
    '30000 - 34000 €',
    'active',
    1,
    1
),
(
    'Intégrateur Front-End',
    'integrateur-front-end',
    'Vous serez chargé de l''intégration HTML, CSS et JavaScript de maquettes fournies par notre équipe design.',
    'Lyon',
    'CDD',
    '28000 - 32000 €',
    'active',
    1,
    2
),
(
    'UI Designer',
    'ui-designer',
    'Vous concevrez des interfaces ergonomiques et participerez à l''amélioration continue de l''expérience utilisateur.',
    'Lyon',
    'CDI',
    '32000 - 36000 €',
    'active',
    2,
    2
),
(
    'Chargé de marketing digital',
    'charge-marketing-digital',
    'Vous piloterez les campagnes publicitaires, le SEO et la stratégie de contenu de plusieurs clients.',
    'Bordeaux',
    'CDI',
    '31000 - 37000 €',
    'active',
    3,
    3
),
(
    'Assistant RH',
    'assistant-rh',
    'Vous accompagnerez l''équipe RH dans la gestion administrative du personnel et le suivi des recrutements.',
    'Nantes',
    'Alternance',
    'Selon profil',
    'active',
    4,
    4
),
(
    'Technicien support informatique',
    'technicien-support-informatique',
    'Vous assurerez le support de niveau 1 et 2 auprès des utilisateurs et participerez au suivi du parc informatique.',
    'Lille',
    'CDI',
    '26000 - 30000 €',
    'active',
    5,
    5
),
(
    'Développeur Symfony confirmé',
    'developpeur-symfony-confirme',
    'Vous rejoindrez une équipe agile en charge du développement de plateformes métiers basées sur Symfony.',
    'Paris',
    'CDI',
    '42000 - 48000 €',
    'active',
    1,
    1
),
(
    'UX Researcher',
    'ux-researcher',
    'Vous conduirez des entretiens utilisateurs, des tests et formulerez des recommandations de conception.',
    'Lyon',
    'Freelance',
    '400 € / jour',
    'active',
    2,
    2
),
(
    'Consultant SEO',
    'consultant-seo',
    'Vous interviendrez sur des audits SEO, l''optimisation technique et éditoriale ainsi que le suivi de performance.',
    'Bordeaux',
    'CDI',
    '35000 - 40000 €',
    'active',
    3,
    3
),
(
    'Administrateur systèmes et réseaux',
    'administrateur-systemes-et-reseaux',
    'Vous assurerez l''administration des serveurs, la supervision et la sécurisation des infrastructures.',
    'Lille',
    'CDI',
    '38000 - 45000 €',
    'active',
    5,
    5
);
