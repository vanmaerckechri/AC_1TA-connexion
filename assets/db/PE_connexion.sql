-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Ven 15 Juin 2018 à 15:51
-- Version du serveur :  5.7.22-0ubuntu18.04.1
-- Version de PHP :  7.2.5-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `PE_connexion`
--

-- --------------------------------------------------------

--
-- Structure de la table `IATA Arts Plastiques_2ème`
--

CREATE TABLE `IATA Arts Plastiques_2ème` (
  `id` int(11) UNSIGNED NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `IATA Arts Plastiques_2ème`
--

INSERT INTO `IATA Arts Plastiques_2ème` (`id`, `nickname`, `password`) VALUES
(1, 'Chri', '987');

-- --------------------------------------------------------

--
-- Structure de la table `PE_adminAccounts`
--

CREATE TABLE `PE_adminAccounts` (
  `id` int(11) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `mail` varchar(78) NOT NULL,
  `activationcode` varchar(64) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `PE_adminAccounts`
--

INSERT INTO `PE_adminAccounts` (`id`, `nickname`, `password`, `mail`, `activationcode`, `activated`) VALUES
(1, 'admin@Chri', '789', 'vanmaerckechri@gmail.com', NULL, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `IATA Arts Plastiques_2ème`
--
ALTER TABLE `IATA Arts Plastiques_2ème`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `PE_adminAccounts`
--
ALTER TABLE `PE_adminAccounts`
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `IATA Arts Plastiques_2ème`
--
ALTER TABLE `IATA Arts Plastiques_2ème`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `PE_adminAccounts`
--
ALTER TABLE `PE_adminAccounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
