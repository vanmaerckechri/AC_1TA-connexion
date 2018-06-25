-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Lun 25 Juin 2018 à 15:09
-- Version du serveur :  5.7.22-0ubuntu18.04.1
-- Version de PHP :  7.2.5-0ubuntu0.18.04.1

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
(31, 'admin@aze', '9adfb0a6d03beb7141d8ec2708d6d9fef9259d12cd230d50f70fb221ae6cabd5', 'aze@aze.com', '0', '1');

-- --------------------------------------------------------

--
-- Structure de la table `pe_classrooms`
--

CREATE TABLE `pe_classrooms` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `pe_classrooms`
--

INSERT INTO `pe_classrooms` (`id`, `id_admin`, `name`) VALUES
(2, 31, 'IATA Arts Plastiques_2ème'),
(3, 2, 'Classe de Test'),
(4, 31, 'Classe Test 3');

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
(2, 31, 2, 'Phil', '789'),
(3, 0, 3, 'Max', '789'),
(7, 31, 4, 'une autre classe', '645'),
(13, 31, 2, 'Chri', '789'),
(15, 31, 2, 'Mike', '456');

--
-- Index pour les tables exportées
--

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
-- Index pour la table `pe_students`
--
ALTER TABLE `pe_students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `pe_adminaccounts`
--
ALTER TABLE `pe_adminaccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pour la table `pe_classrooms`
--
ALTER TABLE `pe_classrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `pe_students`
--
ALTER TABLE `pe_students`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
