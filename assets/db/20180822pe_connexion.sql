-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 22 août 2018 à 20:34
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
-- Structure de la table `1ta_openquestions`
--

DROP TABLE IF EXISTS `1ta_openquestions`;
CREATE TABLE IF NOT EXISTS `1ta_openquestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `serie` varchar(1) NOT NULL,
  `question` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_openquestions`
--

INSERT INTO `1ta_openquestions` (`id`, `id_admin`, `id_classroom`, `serie`, `question`) VALUES
(3, 31, 5, 'A', 'Question ouverte de test?');

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_planets`
--

INSERT INTO `1ta_planets` (`id`, `id_admin`, `id_classroom`, `stats_environnement`, `stats_sante`, `stats_social`) VALUES
(182, 31, 5, 1.16667, 1.02778, 1);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_populations`
--

DROP TABLE IF EXISTS `1ta_populations`;
CREATE TABLE IF NOT EXISTS `1ta_populations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` int(11) NOT NULL,
  `unlocked_theme` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_populations`
--

INSERT INTO `1ta_populations` (`id`, `id_student`, `unlocked_theme`) VALUES
(378, 19, 1),
(379, 21, 1),
(380, 22, 1),
(381, 33, 1);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_replies`
--

DROP TABLE IF EXISTS `1ta_replies`;
CREATE TABLE IF NOT EXISTS `1ta_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` int(11) NOT NULL,
  `serie` varchar(2) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_replies`
--

INSERT INTO `1ta_replies` (`id`, `id_student`, `serie`, `reply1`, `reply2`, `reply3`, `reply4`, `reply5`, `reply6`, `reply7`, `reply8`, `reply9`, `open_reply`) VALUES
(5, 19, 'A', 1, 2, 3, 1, 2, 3, 1, 2, 3, 'kjkhkjhkh');

-- --------------------------------------------------------

--
-- Structure de la table `1ta_stats`
--

DROP TABLE IF EXISTS `1ta_stats`;
CREATE TABLE IF NOT EXISTS `1ta_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_student` int(11) NOT NULL,
  `serie` varchar(7) NOT NULL DEFAULT 'average',
  `stats_envi` float NOT NULL DEFAULT '1',
  `stats_sante` float NOT NULL DEFAULT '1',
  `stats_social` float NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_stats`
--

INSERT INTO `1ta_stats` (`id`, `id_student`, `serie`, `stats_envi`, `stats_sante`, `stats_social`) VALUES
(221, 19, 'average', 1.66667, 1.11111, 1),
(222, 21, 'average', 1, 1, 1),
(223, 22, 'average', 1, 1, 1),
(224, 33, 'average', 1, 1, 1),
(225, 19, 'A', 1.66667, 1.11111, 1);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_adminaccounts`
--

INSERT INTO `pe_adminaccounts` (`id`, `nickname`, `password`, `mail`, `pwdreset`, `activated`) VALUES
(31, 'admin@Chri', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'Chri@aze.com', '0', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_classrooms`
--

INSERT INTO `pe_classrooms` (`id`, `id_admin`, `name`) VALUES
(5, 31, 'Classe de Test 01'),
(6, 31, 'Classe de Test 02'),
(7, 31, 'Classe de Test 03');

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
(1, '1TerreAction', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation amco laboris nisi ut aliquip ex ea commodo consequat.', '1terreaction');

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
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_rel_cr_library`
--

INSERT INTO `pe_rel_cr_library` (`id`, `id_classroom`, `id_library`) VALUES
(89, 5, 1);

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_students`
--

INSERT INTO `pe_students` (`id`, `id_admin`, `id_classroom`, `nickname`, `password`) VALUES
(19, 31, 5, 'Martin', '12345678'),
(21, 31, 5, 'Roger', 'clsEnter'),
(22, 31, 5, 'Cindy', 'azerty'),
(23, 31, 6, 'Marie', 'qwerty'),
(24, 31, 6, 'Jules', 'clscls'),
(25, 31, 6, 'Paul', '123456'),
(26, 31, 6, 'Florence', 'testest'),
(27, 31, 6, 'Tania', 'aniata'),
(28, 31, 7, 'Christophe', '789456'),
(29, 31, 7, 'Alexandre', '456789'),
(30, 31, 7, 'Phil', '123789'),
(31, 31, 7, 'Josette', 'cvmcvm'),
(32, 31, 7, 'Yasmine', 'yuibnv'),
(33, 31, 5, 'Alfred', '123456789');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
