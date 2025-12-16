-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 16 déc. 2025 à 20:07
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jpo`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `commentaire_nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nom ou pseudonyme de l''auteur. (Peut être NULL).',
  `commentaire_ressenti` tinyint(1) NOT NULL DEFAULT '3' COMMENT 'Humeur/Ressenti (ex. 1=pas content, 3=content).',
  `commentaire_note` tinyint(1) NOT NULL DEFAULT '5' COMMENT 'Note d''évaluation principale (ex. 1 à 5).',
  `commentaire_texte` text COLLATE utf8mb4_unicode_ci COMMENT 'Le contenu du commentaire libre.',
  `commentaire_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Statut de l''avis (0=Actif/Publié, 1=Masqué/Modération).',
  `id_public` int UNSIGNED NOT NULL,
  `id_rubrique` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `id_public` (`id_public`),
  KEY `id_rubrique` (`id_rubrique`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `commentaire_nom`, `commentaire_ressenti`, `commentaire_note`, `commentaire_texte`, `commentaire_status`, `id_public`, `id_rubrique`) VALUES
(2, 'patrick', 3, 5, 'je suis le plus super de tous les commentaires ne trouves tu pas ?', 0, 2, 1),
(3, 'jean', 1, 2, 'bon comment dire 1 + 1 c\'est egale a 11', 0, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire_pole`
--

DROP TABLE IF EXISTS `commentaire_pole`;
CREATE TABLE IF NOT EXISTS `commentaire_pole` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_commentaire` int UNSIGNED NOT NULL COMMENT 'Clé Étrangère vers la table commentaire.',
  `id_pole` int UNSIGNED NOT NULL COMMENT 'Clé Étrangère vers la table pole.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_commentaire_pole` (`id_commentaire`,`id_pole`),
  KEY `id_pole` (`id_pole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pole`
--

DROP TABLE IF EXISTS `pole`;
CREATE TABLE IF NOT EXISTS `pole` (
  `id_pole` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `pole_nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Le nom du pôle (ex. "DEV", "CREA", "STRAT").',
  `pole_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Statut d''activité (0=Actif, 1=Inactif/Archivé).',
  PRIMARY KEY (`id_pole`),
  UNIQUE KEY `pole_nom` (`pole_nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `public`
--

DROP TABLE IF EXISTS `public`;
CREATE TABLE IF NOT EXISTS `public` (
  `id_public` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `public_nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Le nom de la catégorie (ex. "LYCEEN", "PARENT").',
  `public_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Statut d''activité (0=Active, 1=Inutilisée).',
  PRIMARY KEY (`id_public`),
  UNIQUE KEY `public_nom` (`public_nom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `public`
--

INSERT INTO `public` (`id_public`, `public_nom`, `public_status`) VALUES
(1, 'parent', 0),
(2, 'etudiant', 0);

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

DROP TABLE IF EXISTS `rubrique`;
CREATE TABLE IF NOT EXISTS `rubrique` (
  `id_rubrique` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `rubrique_nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Le nom de la rubrique (ex. "ACTING", "DOUBLAGE").',
  `rubrique_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Statut d''activité (0=Active, 1=Archivée).',
  PRIMARY KEY (`id_rubrique`),
  UNIQUE KEY `rubrique_nom` (`rubrique_nom`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rubrique`
--

INSERT INTO `rubrique` (`id_rubrique`, `rubrique_nom`, `rubrique_status`) VALUES
(1, 'jeux minecraft', 0),
(2, 'jeux frogit', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_public`) REFERENCES `public` (`id_public`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_rubrique`) REFERENCES `rubrique` (`id_rubrique`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `commentaire_pole`
--
ALTER TABLE `commentaire_pole`
  ADD CONSTRAINT `commentaire_pole_ibfk_1` FOREIGN KEY (`id_commentaire`) REFERENCES `commentaire` (`id_commentaire`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaire_pole_ibfk_2` FOREIGN KEY (`id_pole`) REFERENCES `pole` (`id_pole`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
