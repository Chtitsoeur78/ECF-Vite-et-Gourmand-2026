-- Active: 1782628338358@@127.0.0.1@3306@vite_et_gourmand
CREATE DATABASE vite_et_gourmand
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE vite_et_gourmand;

CREATE TABLE role (
    id_role INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL, 

    PRIMARY KEY (id_role)
);

CREATE TABLE commune_gironde (
    id_commune INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    commune_gironde VARCHAR(70) NOT NULL, 
    kilometrage_bordeaux INT(11) NOT NULL, 
    frais_livraison_euros DECIMAL(4,2),

    PRIMARY KEY (id_commune)
);

CREATE TABLE utilisateurs (
    id_utilisateur INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    id_role INT(11) UNSIGNED NOT NULL,
    raison_sociale VARCHAR(100) NULL,
    pseudo VARCHAR(100) NOT NULL,
    civilite ENUM ('monsieur','madame'),
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email  VARCHAR(150) NOT NULL,
    mot_de_passe VARCHAR(150) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    livraison_adresse_complement VARCHAR(150) NULL,
    livraison_adresse VARCHAR(150) NOT NULL,
    livraison_code_postal VARCHAR(5) NOT NULL,
    livraison_id_commune INT(11) UNSIGNED NOT NULL,
    adresse_facturation_identique TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    facturation_adresse_complement VARCHAR(150) NULL,
    facturation_adresse VARCHAR(150) NULL,
    facturation_code_postal VARCHAR(5) NULL,
    facturation_id_commune INT(11) UNSIGNED NULL,
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_utilisateur),
    UNIQUE (pseudo),
    UNIQUE (email),
    INDEX idx_role(id_role),
    INDEX idx_livraison_id_commune(livraison_id_commune),
    INDEX idx_facturation_id_commune(facturation_id_commune),

    CONSTRAINT fk_utilisateurs_livraison_id_commune
    FOREIGN KEY (livraison_id_commune)
    REFERENCES commune_gironde(id_commune),

    CONSTRAINT fk_utilisateurs_facturation_id_commune
    FOREIGN KEY (facturation_id_commune)
    REFERENCES commune_gironde(id_commune),

    CONSTRAINT fk_utilisateurs_role
    FOREIGN KEY (id_role)
    REFERENCES role(id_role)
);

CREATE TABLE statut_commande (
    id_statut_commande INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    libelle_statut VARCHAR(100) NOT NULL,

    PRIMARY KEY (id_statut_commande),
    UNIQUE (libelle_statut)
);

CREATE TABLE regime (
    id_regime INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    libelle_regime VARCHAR(100) NOT NULL,

    PRIMARY KEY (id_regime),
    UNIQUE (libelle_regime)
);

CREATE TABLE horaires_livraison (
    id_horaire_livraison INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    heure_debut TIME NOT NULL,
    heure_fin TIME NOT NULL,
    tranche_horaire VARCHAR(25) NOT NULL,

    PRIMARY KEY (id_horaire_livraison)
);

CREATE TABLE allergenes (
    id_allergene INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    libelle_allergene VARCHAR(100) NOT NULL,

    PRIMARY KEY (id_allergene),
    UNIQUE (libelle_allergene)
);

CREATE TABLE mot_de_passe_oublie (
    id_mdp_oublie INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    id_utilisateur INT(11) UNSIGNED NOT NULL,
    nouveau_mdp VARCHAR(100) NOT NULL,

    PRIMARY KEY (id_mdp_oublie),

    CONSTRAINT fk_mot_de_passe_oublie_utilisateur
    FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id_utilisateur)
);

CREATE TABLE message_contact (
    id_message_contact INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email  VARCHAR(150) NOT NULL,
    sujet_message ENUM(
       'probleme_commande',
       'probleme_facturation',
       'retour_experience',
       'nouveaux_menus',
       'donnees_personnelles',
       'origine_produits',
       'autres'
    ) NOT NULL,
    titre_message VARCHAR(100) NOT NULL,
    message TEXT NOT NULL, 
    date_envoi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut_message ENUM(
       'recu',
       'lu',
       'repondu'
    ) NOT NULL  DEFAULT 'recu',

    PRIMARY KEY (id_message_contact)
);

CREATE TABLE commandes (
    id_commande INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    id_commune INT(11) UNSIGNED NOT NULL,
    id_utilisateur INT(11) UNSIGNED NOT NULL,
    id_statut_commande INT(11) UNSIGNED NOT NULL,
    id_horaire_livraison INT(11) UNSIGNED NOT NULL,
    date_livraison DATE NOT NULL,
    nom_menu VARCHAR(100) NOT NULL,
    nombre_entree1 INT(4) UNSIGNED NOT NULL,
    nombre_entree2 INT(4) UNSIGNED NOT NULL,
    nombre_dessert1 INT(4) UNSIGNED NOT NULL,
    nombre_dessert2 INT(4) UNSIGNED NOT NULL,
    nombre_personnes INT(4) UNSIGNED NOT NULL,
    prix_menu DECIMAL(6,2) NOT NULL,
    frais_livraison_euros DECIMAL(4,2) NOT NULL,
    prix_total DECIMAL(6,2) NOT NULL,
    pret_materiel TINYINT(1) NOT NULL,
    retour_materiel TINYINT(1) NOT NULL,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_commande),
 
    INDEX idx_utilisateur(id_utilisateur),
    INDEX idx_commune(id_commune),
    INDEX idx_statut_commande(id_statut_commande),
    INDEX idx_horaires_livraison(id_horaire_livraison),

    CONSTRAINT fk_commande_id_commune
    FOREIGN KEY (id_commune)
    REFERENCES commune_gironde(id_commune), 

    CONSTRAINT fk_commande_id_utilisateur
    FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id_utilisateur),

    CONSTRAINT fk_commande_id_statut_commande
    FOREIGN KEY (id_statut_commande)
    REFERENCES statut_commande(id_statut_commande),

    CONSTRAINT fk_commande_horaire_livraison
    FOREIGN KEY (id_horaire_livraison)
    REFERENCES horaires_livraison(id_horaire_livraison)
);

CREATE TABLE plat (
    id_plat INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    nom_plat VARCHAR(100) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    
    PRIMARY KEY (id_plat)
);

CREATE TABLE menus (
    id_menu INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    nom_menu VARCHAR(100) NOT NULL,
    id_plat INT(11) UNSIGNED NOT NULL,
    id_regime INT(11) UNSIGNED NOT NULL,
    nb_personnes_minimum INT(11) UNSIGNED NOT NULL,
    prix_par_personne_euros DECIMAL(6,2) NOT NULL,
    description_menu TEXT NOT NULL,

    PRIMARY KEY (id_menu),
 
    INDEX idx_plat(id_plat),
    INDEX idx_regime(id_regime),
    
    CONSTRAINT fk_menus_id_plat
    FOREIGN KEY (id_plat)
    REFERENCES plat(id_plat), 

    CONSTRAINT fk_menus_id_regime
    FOREIGN KEY (id_regime)
    REFERENCES regime(id_regime)
);

CREATE TABLE salaries (
    id_salarie INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    civilite ENUM ('monsieur','madame'),
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    id_role INT(11) UNSIGNED NOT NULL,
    email  VARCHAR(150) NOT NULL,
    mot_de_passe VARCHAR(150) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    adresse VARCHAR(150) NOT NULL,
    code_postal VARCHAR(5) NOT NULL,
    id_commune INT(11) UNSIGNED NOT NULL,
    date_embauche DATE,
    type_contrat ENUM ('CDI','CDD','stagiaire','interimaire') NOT NULL,
    fonction VARCHAR(250) NOT NULL,
    date_prise_fonction DATE NOT NULL, 
    date_fin_contrat DATE NULL,  -- Le mot contrat a été tapé avec une faute de frappe puis corrigé : correction ci-dessous
    date_creation_compte DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_salarie),

    CONSTRAINT fk_salaries_id_role
    FOREIGN KEY (id_role)
    REFERENCES role(id_role), 

    CONSTRAINT fk_salaries_id_commune
    FOREIGN KEY (id_commune)
    REFERENCES commune_gironde(id_commune)
);

-- Modification d'un nom de colonne après un constat de faute de frappe
ALTER TABLE salaries
CHANGE COLUMN date_fin_contat date_fin_contrat DATE NULL;

-- Vérification de la correction de la faute de frappe 
SHOW CREATE TABLE salaries;
DESCRIBE salaries;

-- TABLES ASSOCIATIVE (OU D'ASSOCIATION)
-- PLAT ET ALLERGENE : Le plat peut avoir plusieurs allergènes et les allergènes être dans plusieurs plats
CREATE TABLE plat_allergenes (
    id_plat INT(11) UNSIGNED NOT NULL,
    id_allergene INT(11) UNSIGNED NOT NULL,
    
    PRIMARY KEY (id_plat, id_allergene),

    FOREIGN KEY (id_plat)
    REFERENCES plat(id_plat),

    FOREIGN KEY (id_allergene)
    REFERENCES allergenes(id_allergene)
);   



