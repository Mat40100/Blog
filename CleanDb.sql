-- phpMyAdmin SQL Dump
-- version 4.1.14.8
-- http://www.phpmyadmin.net
--
-- Client :  db732316432.db.1and1.com
-- Généré le :  Dim 29 Avril 2018 à 08:55
-- Version du serveur :  5.5.59-0+deb7u1-log
-- Version de PHP :  5.4.45-0+deb7u13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db732316432`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '0',
  `last_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `last_mod` datetime DEFAULT NULL,
  `comment` text COLLATE latin1_general_ci,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- Structure de la table `connexion_failed`
--

CREATE TABLE IF NOT EXISTS `connexion_failed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=118 ;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `authorid` int(11) DEFAULT NULL,
  `title` text COLLATE latin1_general_ci,
  `last_mod` datetime DEFAULT NULL,
  `chapo` text COLLATE latin1_general_ci,
  `content` text COLLATE latin1_general_ci,
  PRIMARY KEY (`postid`),
  KEY `AuthorId` (`authorid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `pwd` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `userlvl` smallint(1) DEFAULT NULL,
  `nickname` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `first_name` varchar(25) COLLATE latin1_general_ci DEFAULT NULL,
  `last_name` varchar(35) COLLATE latin1_general_ci DEFAULT NULL,
  `chapo` text COLLATE latin1_general_ci,
  `email` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `adress` text COLLATE latin1_general_ci,
  `github` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `linkedin` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `pdf` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `Nickname` (`nickname`),
  UNIQUE KEY `Email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`AuthorId`) REFERENCES `users` (`UserId`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
