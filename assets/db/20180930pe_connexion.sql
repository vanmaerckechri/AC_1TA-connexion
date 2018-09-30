-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 30 sep. 2018 à 15:25
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
  `activation` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_planets`
--

INSERT INTO `1ta_planets` (`id`, `id_admin`, `id_classroom`, `stats_environnement`, `stats_sante`, `stats_social`, `activation`) VALUES
(195, 31, 6, 1, 1, 1, 0),
(197, 31, 5, 1.0275, 1.0275, 1.0275, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=449 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_populations`
--

INSERT INTO `1ta_populations` (`id`, `id_student`, `unlocked_theme`) VALUES
(427, 23, 1),
(428, 24, 1),
(429, 25, 1),
(430, 26, 1),
(431, 27, 1),
(445, 19, 1),
(446, 21, 1),
(447, 22, 1),
(448, 33, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_replies`
--

INSERT INTO `1ta_replies` (`id`, `id_student`, `serie`, `reply1`, `reply2`, `reply3`, `reply4`, `reply5`, `reply6`, `reply7`, `reply8`, `reply9`, `open_reply`) VALUES
(6, 19, 'alimentation', 2, 2, 2, 2, 1, 2, 1, 2, 2, 'test');

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
) ENGINE=InnoDB AUTO_INCREMENT=278 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_stats`
--

INSERT INTO `1ta_stats` (`id`, `id_student`, `serie`, `stats_envi`, `stats_sante`, `stats_social`) VALUES
(276, 19, 'alimentation', 1.11, 1.11, 1.11),
(277, 19, 'average', 1.11, 1.11, 1.11);

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `1ta_themes`
--

INSERT INTO `1ta_themes` (`id`, `id_admin`, `id_classroom`, `theme`, `openquestion`, `activation`) VALUES
(14, 31, 6, 'alimentation', 'test alim p1', 1),
(10, 31, 6, 'test', 'question planete 1 test', 1),
(9, 31, 5, 'test', 'question ouverte Test (planète test 01)', 0),
(13, 31, 5, 'alimentation', 'question ouverte alimentation planète test 2 <script>alert(\"coucou\")</script>', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_classrooms`
--

INSERT INTO `pe_classrooms` (`id`, `id_admin`, `name`) VALUES
(5, 31, 'Classe de Test 01'),
(6, 31, 'Classe de Test 02'),
(8, 31, 'Classe de Test 03');

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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_rel_cr_library`
--

INSERT INTO `pe_rel_cr_library` (`id`, `id_classroom`, `id_library`) VALUES
(112, 5, 1);

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
  `avatar_tete` varchar(100) NOT NULL DEFAULT '1',
  `avatar_yeux` varchar(100) NOT NULL DEFAULT '1',
  `avatar_lunettes` varchar(100) NOT NULL DEFAULT '1',
  `avatar_bouche` varchar(100) NOT NULL DEFAULT '1',
  `avatar_cheveux` varchar(100) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_students`
--

INSERT INTO `pe_students` (`id`, `id_admin`, `id_classroom`, `nickname`, `password`, `avatar_tete`, `avatar_yeux`, `avatar_lunettes`, `avatar_bouche`, `avatar_cheveux`) VALUES
(19, 31, 5, 'Martin', '12345678', 'avatar_tete01col04.svg', 'avatar_yeux03col01.svg', 'avatar_lunettes02col01.svg', 'avatar_bouche02col01.svg', 'avatar_cheveux02col01.svg'),
(21, 31, 5, 'Roger', 'clsEnter', '1', '1', '1', '1', '1'),
(22, 31, 5, 'Cindy', 'azerty', '1', '1', '1', '1', '1'),
(23, 31, 6, 'Marie', 'qwerty', '1', '1', '1', '1', '1'),
(24, 31, 6, 'Jules', 'clscls', '1', '1', '1', '1', '1'),
(25, 31, 6, 'Paul', '123456', '1', '1', '1', '1', '1'),
(26, 31, 6, 'Florence', 'testest', '1', '1', '1', '1', '1'),
(27, 31, 6, 'Tania', 'aniata', '1', '1', '1', '1', '1'),
(33, 31, 5, 'Alfred', '123456789', '1', '1', '1', '1', '1'),
(38, 31, 8, 'Chri01', 'Chri01', '1', '1', '1', '1', '1'),
(39, 31, 8, 'Chri02', 'Chri01', '1', '1', '1', '1', '1'),
(40, 31, 8, 'Chri03', 'Chri01', '1', '1', '1', '1', '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
