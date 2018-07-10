-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 10 juil. 2018 à 21:16
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_planets`
--

INSERT INTO `1ta_planets` (`id`, `id_admin`, `id_classroom`) VALUES
(40, 31, 2);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_populations`
--

DROP TABLE IF EXISTS `1ta_populations`;
CREATE TABLE IF NOT EXISTS `1ta_populations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `stats_water` int(1) NOT NULL DEFAULT '3',
  `stats_air` int(1) NOT NULL DEFAULT '3',
  `stats_forest` int(1) NOT NULL DEFAULT '3',
  `stats_average` int(1) NOT NULL DEFAULT '3',
  `openanswer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_populations`
--

INSERT INTO `1ta_populations` (`id`, `id_admin`, `id_classroom`, `id_student`, `stats_water`, `stats_air`, `stats_forest`, `stats_average`, `openanswer`) VALUES
(15, 31, 2, 13, 1, 2, 2, 2, NULL),
(18, 31, 2, 17, 2, 2, 2, 2, NULL),
(17, 31, 2, 16, 1, 1, 3, 2, NULL),
(16, 31, 2, 15, 3, 3, 1, 2, NULL),
(14, 31, 2, 2, 3, 3, 3, 3, NULL);

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
(31, 'admin@Chri', '9adfb0a6d03beb7141d8ec2708d6d9fef9259d12cd230d50f70fb221ae6cabd5', 'Chri@aze.com', '0', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_classrooms`
--

INSERT INTO `pe_classrooms` (`id`, `id_admin`, `name`) VALUES
(2, 31, 'IATA Arts Plastiques'),
(4, 31, 'Classe de Test n°1');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_library`
--

INSERT INTO `pe_library` (`id`, `name`, `description`, `folder`) VALUES
(1, '1TerreAction', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation amco laboris nisi ut aliquip ex ea commodo consequat.', '1terreaction');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_students`
--

INSERT INTO `pe_students` (`id`, `id_admin`, `id_classroom`, `nickname`, `password`) VALUES
(2, 31, 2, 'Philippe', '98745612'),
(7, 31, 4, 'une autre classe', '645'),
(13, 31, 2, 'Christophe', '13245678'),
(15, 31, 2, 'Simon', 'azerty'),
(16, 31, 2, 'Cindy', 'clsEnter'),
(17, 31, 2, 'Albert', 'opopop');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
