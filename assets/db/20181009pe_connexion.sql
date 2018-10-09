-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mar 09 Octobre 2018 à 16:26
-- Version du serveur :  5.7.23-0ubuntu0.18.04.1
-- Version de PHP :  7.2.10-0ubuntu0.18.04.1

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
  `stats_environnement` float NOT NULL DEFAULT '1',
  `stats_sante` float NOT NULL DEFAULT '1',
  `stats_social` float NOT NULL DEFAULT '1',
  `activation` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `1ta_planets`
--

INSERT INTO `1ta_planets` (`id`, `id_admin`, `id_classroom`, `stats_environnement`, `stats_sante`, `stats_social`, `activation`) VALUES
(195, 31, 6, 1, 1, 1, 1),
(197, 31, 5, 0.86, 0.86, 0.86, 1),
(198, 31, 8, 1, 1, 1, 1),
(209, 32, 21, 0.61, 0.61, 0.61, 1);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_replies`
--

CREATE TABLE `1ta_replies` (
  `id` int(11) NOT NULL,
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
  `open_reply` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `1ta_replies`
--

INSERT INTO `1ta_replies` (`id`, `id_student`, `serie`, `reply1`, `reply2`, `reply3`, `reply4`, `reply5`, `reply6`, `reply7`, `reply8`, `reply9`, `open_reply`) VALUES
(6, 19, 'alimentation', 1, 2, 1, 1, 2, 1, 1, 2, 1, ''),
(7, 74, 'alimentation', 1, 1, 1, 1, 1, 1, 1, 2, 1, 'test');

-- --------------------------------------------------------

--
-- Structure de la table `1ta_stats`
--

CREATE TABLE `1ta_stats` (
  `id` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `serie` varchar(20) NOT NULL DEFAULT 'average',
  `stats_envi` float NOT NULL DEFAULT '1',
  `stats_sante` float NOT NULL DEFAULT '1',
  `stats_social` float NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `1ta_stats`
--

INSERT INTO `1ta_stats` (`id`, `id_student`, `serie`, `stats_envi`, `stats_sante`, `stats_social`) VALUES
(276, 19, 'alimentation', 0.44, 0.44, 0.44),
(277, 19, 'average', 0.44, 0.44, 0.44),
(278, 74, 'alimentation', 0.22, 0.22, 0.22),
(279, 74, 'average', 0.22, 0.22, 0.22);

-- --------------------------------------------------------

--
-- Structure de la table `1ta_themes`
--

CREATE TABLE `1ta_themes` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `theme` varchar(20) NOT NULL,
  `openquestion` varchar(512) NOT NULL DEFAULT '',
  `activation` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `1ta_themes`
--

INSERT INTO `1ta_themes` (`id`, `id_admin`, `id_classroom`, `theme`, `openquestion`, `activation`) VALUES
(14, 31, 6, 'alimentation', 'test alim p1', 1),
(10, 31, 6, 'test', 'question planete 1 test', 0),
(9, 31, 5, 'test', 'question ouverte Test (planète test 01)', 0),
(13, 31, 5, 'alimentation', 'question ouverte alimentation planète test 2 <script>alert(\"coucou\")</script>', 1),
(15, 31, 8, 'test', '', 0);

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
  `activated` varchar(64) NOT NULL DEFAULT '0',
  `newMailCode` varchar(8) NOT NULL DEFAULT '0',
  `deleteAccountCode` varchar(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pe_adminaccounts`
--

INSERT INTO `pe_adminaccounts` (`id`, `nickname`, `password`, `mail`, `pwdreset`, `activated`, `newMailCode`, `deleteAccountCode`) VALUES
(31, 'admin@Chri', 'ed968e840d10d2d313a870bc131a4e2c311d7ad09bdf32b3418147221f51a6e2', 'test@test.com', '0', '1', 'PAWe2f1i', 'nepHVBqr'),
(32, 'admin@test', 'ed968e840d10d2d313a870bc131a4e2c311d7ad09bdf32b3418147221f51a6e2', 'c@c.com', '0', '1', 'phIUN0PB', '0j0ijJwI');

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
(8, 31, 'Classe de Test 03'),
(21, 32, 'tttttttt');

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

--
-- Contenu de la table `pe_rel_cr_library`
--

INSERT INTO `pe_rel_cr_library` (`id`, `id_classroom`, `id_library`) VALUES
(112, 5, 1),
(113, 8, 1),
(114, 6, 1),
(124, 21, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pe_students`
--

CREATE TABLE `pe_students` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_admin` int(11) NOT NULL,
  `id_classroom` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `avatar_tete` varchar(100) NOT NULL DEFAULT '1',
  `avatar_yeux` varchar(100) NOT NULL DEFAULT '1',
  `avatar_lunettes` varchar(100) NOT NULL DEFAULT '1',
  `avatar_bouche` varchar(100) NOT NULL DEFAULT '1',
  `avatar_cheveux` varchar(100) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `pe_students`
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
(40, 31, 8, 'Chri03', 'Chri01', 'avatar_tete01col01.svg', 'avatar_yeux01col01.svg', 'avatar_lunettes01col01.svg', 'avatar_bouche01col01.svg', 'avatar_cheveux01col01.svg'),
(74, 32, 21, 'azerty', '12345', 'avatar_tete01col01.svg', 'avatar_yeux01col01.svg', 'avatar_lunettes01col01.svg', 'avatar_bouche01col01.svg', 'avatar_cheveux01col01.svg'),
(75, 32, 21, 'qwerty', '12345', '1', '1', '1', '1', '1');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `1ta_planets`
--
ALTER TABLE `1ta_planets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `1ta_replies`
--
ALTER TABLE `1ta_replies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `1ta_stats`
--
ALTER TABLE `1ta_stats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `1ta_themes`
--
ALTER TABLE `1ta_themes`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;
--
-- AUTO_INCREMENT pour la table `1ta_replies`
--
ALTER TABLE `1ta_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `1ta_stats`
--
ALTER TABLE `1ta_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;
--
-- AUTO_INCREMENT pour la table `1ta_themes`
--
ALTER TABLE `1ta_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `pe_adminaccounts`
--
ALTER TABLE `pe_adminaccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pour la table `pe_classrooms`
--
ALTER TABLE `pe_classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `pe_library`
--
ALTER TABLE `pe_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `pe_rel_cr_library`
--
ALTER TABLE `pe_rel_cr_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT pour la table `pe_students`
--
ALTER TABLE `pe_students`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
