-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Lun 18 Juin 2018 à 16:07
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
  `pwdreset` varchar(64) NOT NULL DEFAULT '0',
  `activated` varchar(64) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `PE_adminAccounts`
--

INSERT INTO `PE_adminAccounts` (`id`, `nickname`, `password`, `mail`, `pwdreset`, `activated`) VALUES
(16, 'admin@test', '35a9e381b1a27567549b5f8a6f783c167ebf809f1c4d6a9e367240484d8ce281', 'aze@aze.com', '0', '1'),
(17, 'admin@azerty', '35a9e381b1a27567549b5f8a6f783c167ebf809f1c4d6a9e367240484d8ce281', 'aaa@ccc.com', '0', '1'),
(19, 'admin@chri', '35a9e381b1a27567549b5f8a6f783c167ebf809f1c4d6a9e367240484d8ce281', 'test@test.com', '0', '1'),
(26, 'admin@testrezzer', '35a9e381b1a27567549b5f8a6f783c167ebf809f1c4d6a9e367240484d8ce281', 'ert@tre.comzer', '0', '1'),
(27, 'admin@testt', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'test@tzest.com', '0', '1'),
(28, 'admin@testt12', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'tes12t@tzest.com', '0', '1'),
(29, 'admin@test789', '35a9e381b1a27567549b5f8a6f783c167ebf809f1c4d6a9e367240484d8ce281', 'tes78t@test.com', '0', '1'),
(30, 'admin@testtest', '35a9e381b1a27567549b5f8a6f783c167ebf809f1c4d6a9e367240484d8ce281', 'te78979798st@test.com', '0', '1');

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
