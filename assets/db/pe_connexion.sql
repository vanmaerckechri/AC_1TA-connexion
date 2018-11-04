-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 04 nov. 2018 à 17:16
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `pe_connexion`
--

-- --------------------------------------------------------

--
-- Structure de la table `1ta_planets`
--

DROP TABLE IF EXISTS `1ta_planets`;
CREATE TABLE IF NOT EXISTS `1ta_planets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `stats_environnement` float NOT NULL DEFAULT '1',
  `stats_sante` float NOT NULL DEFAULT '1',
  `stats_social` float NOT NULL DEFAULT '1',
  `activation` tinyint(1) NOT NULL DEFAULT '1',
  `virgin` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `1ta_replies`
--

DROP TABLE IF EXISTS `1ta_replies`;
CREATE TABLE IF NOT EXISTS `1ta_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` int(11) NOT NULL,
  `serie` varchar(20) NOT NULL,
  `reply1` int(1) NOT NULL,
  `reply2` int(1) NOT NULL,
  `reply3` int(1) NOT NULL,
  `reply4` int(1) NOT NULL,
  `reply5` int(1) NOT NULL,
  `reply6` int(1) NOT NULL,
  `reply7` int(1) NOT NULL,
  `reply8` int(1) NOT NULL,
  `reply9` int(1) NOT NULL,
  `open_reply` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `1ta_stats`
--

DROP TABLE IF EXISTS `1ta_stats`;
CREATE TABLE IF NOT EXISTS `1ta_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` int(11) NOT NULL,
  `serie` varchar(20) NOT NULL DEFAULT 'average',
  `stats_envi` float NOT NULL DEFAULT '1',
  `stats_sante` float NOT NULL DEFAULT '1',
  `stats_social` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `1ta_themes`
--

DROP TABLE IF EXISTS `1ta_themes`;
CREATE TABLE IF NOT EXISTS `1ta_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `theme` varchar(20) NOT NULL,
  `openquestion` varchar(512) NOT NULL DEFAULT '',
  `activation` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pe_adminaccounts`
--

DROP TABLE IF EXISTS `pe_adminaccounts`;
CREATE TABLE IF NOT EXISTS `pe_adminaccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `mail` varchar(78) NOT NULL,
  `pwdreset` varchar(64) NOT NULL DEFAULT '0',
  `activated` varchar(64) NOT NULL DEFAULT '0',
  `newMailCode` varchar(8) NOT NULL DEFAULT '0',
  `deleteAccountCode` varchar(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pe_classrooms`
--

DROP TABLE IF EXISTS `pe_classrooms`;
CREATE TABLE IF NOT EXISTS `pe_classrooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pe_library`
--

DROP TABLE IF EXISTS `pe_library`;
CREATE TABLE IF NOT EXISTS `pe_library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` varchar(512) NOT NULL,
  `folder` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_library`
--

INSERT INTO `pe_library` (`id`, `name`, `description`, `folder`) VALUES
(1, '1TerreAction', 'pas de description', '1terreaction');

-- --------------------------------------------------------

--
-- Structure de la table `pe_rel_cr_library`
--

DROP TABLE IF EXISTS `pe_rel_cr_library`;
CREATE TABLE IF NOT EXISTS `pe_rel_cr_library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classroom` int(11) NOT NULL,
  `id_library` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pe_students`
--

DROP TABLE IF EXISTS `pe_students`;
CREATE TABLE IF NOT EXISTS `pe_students` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `avatar_tete` varchar(7) NOT NULL DEFAULT '1',
  `avatar_yeux` varchar(7) NOT NULL DEFAULT '1',
  `avatar_lunettes` varchar(7) NOT NULL DEFAULT '1',
  `avatar_bouche` varchar(7) NOT NULL DEFAULT '1',
  `avatar_cheveux` varchar(7) NOT NULL DEFAULT '1',
  `avatar_corps` varchar(7) NOT NULL DEFAULT '1',
  `avatar_back` varchar(7) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
