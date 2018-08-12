-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 12 août 2018 à 18:48
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
  `stats_environnement` int(1) NOT NULL DEFAULT '3',
  `stats_sante` int(1) NOT NULL DEFAULT '3',
  `stats_social` int(1) NOT NULL DEFAULT '3',
  `stats_average` int(1) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_planets`
--

INSERT INTO `1ta_planets` (`id`, `id_admin`, `id_classroom`, `stats_environnement`, `stats_sante`, `stats_social`, `stats_average`) VALUES
(128, 31, 5, 3, 3, 3, 3);

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
  `unlocked_theme` int(11) NOT NULL DEFAULT '1',
  `stats_environnement` int(1) NOT NULL DEFAULT '1',
  `stats_sante` int(1) NOT NULL DEFAULT '1',
  `stats_social` int(1) NOT NULL DEFAULT '1',
  `stats_average` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_populations`
--

INSERT INTO `1ta_populations` (`id`, `id_admin`, `id_classroom`, `id_student`, `unlocked_theme`, `stats_environnement`, `stats_sante`, `stats_social`, `stats_average`) VALUES
(142, 31, 5, 19, 1, 3, 3, 3, 1),
(144, 31, 5, 21, 1, 1, 1, 1, 1),
(145, 31, 5, 22, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_rel_questions_propositions`
--

DROP TABLE IF EXISTS `1ta_rel_questions_propositions`;
CREATE TABLE IF NOT EXISTS `1ta_rel_questions_propositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_question` int(11) NOT NULL,
  `id_proposition` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_rel_questions_propositions`
--

INSERT INTO `1ta_rel_questions_propositions` (`id`, `id_question`, `id_proposition`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(5, 2, 5),
(6, 2, 6),
(7, 2, 7),
(8, 3, 8),
(9, 3, 9),
(10, 3, 10),
(11, 4, 11),
(12, 4, 12),
(13, 4, 13);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_results_by_themes`
--

DROP TABLE IF EXISTS `1ta_results_by_themes`;
CREATE TABLE IF NOT EXISTS `1ta_results_by_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `theme` varchar(32) NOT NULL DEFAULT 'Repas',
  `stats_environnement` int(1) NOT NULL DEFAULT '1',
  `stats_sante` int(1) NOT NULL DEFAULT '1',
  `stats_social` int(1) NOT NULL DEFAULT '1',
  `open_question` varchar(512) NOT NULL DEFAULT '',
  `open_answer` varchar(2048) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_results_by_themes`
--

INSERT INTO `1ta_results_by_themes` (`id`, `id_admin`, `id_classroom`, `id_student`, `theme`, `stats_environnement`, `stats_sante`, `stats_social`, `open_question`, `open_answer`) VALUES
(27, 31, 5, 19, 'Repas', 1, 1, 1, '', ''),
(29, 31, 5, 21, 'Repas', 1, 1, 1, '', ''),
(30, 31, 5, 22, 'Repas', 1, 1, 1, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `1ta_theme_propositions`
--

DROP TABLE IF EXISTS `1ta_theme_propositions`;
CREATE TABLE IF NOT EXISTS `1ta_theme_propositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `propositions` varchar(32) NOT NULL,
  `src_img` varchar(32) NOT NULL,
  `stats_environnement` int(11) NOT NULL,
  `stats_sante` int(11) NOT NULL,
  `stats_social` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_theme_propositions`
--

INSERT INTO `1ta_theme_propositions` (`id`, `propositions`, `src_img`, `stats_environnement`, `stats_sante`, `stats_social`) VALUES
(1, 'pomme', 'fruit_pomme', 3, 3, 3),
(2, 'banane', 'fruit_banane', 2, 2, 2),
(3, 'kiwi', 'fruit_kiwi', 1, 1, 1),
(5, 'q2 proposition A', 'fruit_banane', 2, 2, 2),
(6, 'q2 proposition B', 'fruit_banane', 1, 1, 1),
(7, 'q2 proposition C', 'fruit_banane', 3, 3, 3),
(8, 'q3 proposition A', 'fruit_kiwi', 2, 2, 2),
(9, 'q3 proposition B', 'fruit_kiwi', 1, 3, 2),
(10, 'q3 proposition C', 'fruit_kiwi', 3, 1, 2),
(11, 'q1 proposition A', 'fruit_pomme', 1, 2, 3),
(12, 'q1 proposition B', 'fruit_pomme', 2, 2, 1),
(13, 'q1 proposition C', 'fruit_pomme', 2, 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_theme_questions`
--

DROP TABLE IF EXISTS `1ta_theme_questions`;
CREATE TABLE IF NOT EXISTS `1ta_theme_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_position` int(11) NOT NULL,
  `theme_name` varchar(32) NOT NULL,
  `question` varchar(255) NOT NULL,
  `src_img` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_theme_questions`
--

INSERT INTO `1ta_theme_questions` (`id`, `theme_position`, `theme_name`, `question`, `src_img`) VALUES
(1, 1, 'Repas', 'Quel fruit manges-tu pour ton petit déjeuner ?', 'alim_bg01'),
(2, 1, 'Repas', 'theme repas - questionnaire A - question 02', 'alim_bg01'),
(3, 1, 'Repas', 'theme repas - questionnaire A - question 03', 'alim_bg01'),
(4, 1, 'Repas', 'theme repas - questionnaire B - question 01', 'alim_bg02'),
(5, 1, 'Repas', 'theme repas - questionnaire B - question 02', 'alim_bg02'),
(6, 1, 'Repas', 'theme repas - questionnaire B - question 03', 'alim_bg02'),
(7, 1, 'Repas', 'theme repas - questionnaire C - question 01', 'alim_bg03'),
(8, 1, 'Repas', 'theme repas - questionnaire C - question 02', 'alim_bg03'),
(9, 1, 'Repas', 'theme repas - questionnaire C - question 03', 'alim_bg03'),
(10, 2, 'Transports', 'theme transports - questionnaire A - question 01', 'test');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_rel_cr_library`
--

INSERT INTO `pe_rel_cr_library` (`id`, `id_classroom`, `id_library`) VALUES
(35, 5, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

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
(32, 31, 7, 'Yasmine', 'yuibnv');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
