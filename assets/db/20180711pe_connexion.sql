-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mer 11 Juillet 2018 à 15:30
-- Version du serveur :  5.7.22-0ubuntu18.04.1
-- Version de PHP :  7.2.7-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

CREATE TABLE `1ta_planets` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `stats_water` int(1) NOT NULL DEFAULT '3',
  `stats_air` int(1) NOT NULL DEFAULT '3',
  `stats_forest` int(1) NOT NULL DEFAULT '3',
  `stats_average` int(1) NOT NULL DEFAULT '3'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `1ta_populations`
--

CREATE TABLE `1ta_populations` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `stats_water` int(1) NOT NULL DEFAULT '3',
  `stats_air` int(1) NOT NULL DEFAULT '3',
  `stats_forest` int(1) NOT NULL DEFAULT '3',
  `stats_average` int(1) NOT NULL DEFAULT '3',
  `openanswer` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pe_adminaccounts`
--

CREATE TABLE `pe_adminaccounts` (
  `id` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `mail` varchar(78) NOT NULL,
  `pwdreset` varchar(64) NOT NULL DEFAULT '0',
  `activated` varchar(64) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pe_adminaccounts`
--

INSERT INTO `pe_adminaccounts` (`id`, `nickname`, `password`, `mail`, `pwdreset`, `activated`) VALUES
(31, 'admin@Chri', 'f3029a66c61b61b41b428963a2fc134154a5383096c776f3b4064733c5463d90', 'Chri@aze.com', '0', '1');

-- --------------------------------------------------------

--
-- Structure de la table `pe_classrooms`
--

CREATE TABLE `pe_classrooms` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pe_classrooms`
--

INSERT INTO `pe_classrooms` (`id`, `id_admin`, `name`) VALUES
(5, 31, 'Classe de Test 01'),
(6, 31, 'Classe de Test 02'),
(7, 31, 'Classe de Test 03');

-- --------------------------------------------------------

--
-- Structure de la table `pe_library`
--

CREATE TABLE `pe_library` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(512) NOT NULL,
  `folder` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pe_library`
--

INSERT INTO `pe_library` (`id`, `name`, `description`, `folder`) VALUES
(1, '1TerreAction', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation amco laboris nisi ut aliquip ex ea commodo consequat.', '1terreaction');

-- --------------------------------------------------------

--
-- Structure de la table `pe_rel_cr_library`
--

CREATE TABLE `pe_rel_cr_library` (
  `id` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `id_library` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pe_students`
--

CREATE TABLE `pe_students` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pe_students`
--

INSERT INTO `pe_students` (`id`, `id_admin`, `id_classroom`, `nickname`, `password`) VALUES
(19, 31, 5, 'Martin', '12345678'),
(20, 31, 5, 'Martine', '987456321'),
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

--
-- Index pour les tables exportées
--

--
-- Index pour la table `1ta_planets`
--
ALTER TABLE `1ta_planets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `1ta_populations`
--
ALTER TABLE `1ta_populations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pe_adminaccounts`
--
ALTER TABLE `pe_adminaccounts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pe_classrooms`
--
ALTER TABLE `pe_classrooms`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pe_library`
--
ALTER TABLE `pe_library`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pe_rel_cr_library`
--
ALTER TABLE `pe_rel_cr_library`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pe_students`
--
ALTER TABLE `pe_students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `1ta_planets`
--
ALTER TABLE `1ta_planets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT pour la table `1ta_populations`
--
ALTER TABLE `1ta_populations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT pour la table `pe_adminaccounts`
--
ALTER TABLE `pe_adminaccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pour la table `pe_classrooms`
--
ALTER TABLE `pe_classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `pe_library`
--
ALTER TABLE `pe_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `pe_rel_cr_library`
--
ALTER TABLE `pe_rel_cr_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `pe_students`
--
ALTER TABLE `pe_students`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
