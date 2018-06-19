-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 19 juin 2018 à 16:19
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
-- Structure de la table `iata arts plastiques_2ème`
--

DROP TABLE IF EXISTS `iata arts plastiques_2ème`;
CREATE TABLE IF NOT EXISTS `iata arts plastiques_2ème` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `iata arts plastiques_2ème`
--

INSERT INTO `iata arts plastiques_2ème` (`id`, `nickname`, `password`) VALUES
(1, 'chri', '9adfb0a6d03beb7141d8ec2708d6d9fef9259d12cd230d50f70fb221ae6cabd5');

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pe_adminaccounts`
--

INSERT INTO `pe_adminaccounts` (`id`, `nickname`, `password`, `mail`, `pwdreset`, `activated`) VALUES
(34, 'admin@chri', '9adfb0a6d03beb7141d8ec2708d6d9fef9259d12cd230d50f70fb221ae6cabd5', 'ccc@ccc.com', '0', '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
