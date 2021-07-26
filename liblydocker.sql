-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : database
-- Généré le : lun. 26 juil. 2021 à 11:40
-- Version du serveur : 5.7.35
-- Version de PHP : 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `liblydocker`
--

-- --------------------------------------------------------

--
-- Structure de la table `lbly_article`
--

CREATE TABLE `lbly_article` (
                                `id` int(11) NOT NULL,
                                `author` int(11) NOT NULL DEFAULT '0',
                                `title` text NOT NULL,
                                `slug` varchar(200) NOT NULL,
                                `content` longtext NOT NULL,
                                `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `published` datetime DEFAULT NULL,
                                `modified` datetime DEFAULT NULL,
                                `status` varchar(20) NOT NULL DEFAULT 'publish'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lbly_books`
--

CREATE TABLE `lbly_books` (
                              `id` int(11) NOT NULL,
                              `title` varchar(255) COLLATE utf8_bin NOT NULL,
                              `description` text COLLATE utf8_bin,
                              `author` varchar(50) COLLATE utf8_bin NOT NULL,
                              `publication_date` date NOT NULL,
                              `image` text COLLATE utf8_bin,
                              `publisher` varchar(255) COLLATE utf8_bin NOT NULL,
                              `price` smallint(6) NOT NULL,
                              `category` varchar(255) COLLATE utf8_bin DEFAULT NULL,
                              `stock_number` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `lbly_category`
--

CREATE TABLE `lbly_category` (
                                 `id` int(11) NOT NULL,
                                 `nameCategory` varchar(255) NOT NULL,
                                 `colorCategory` varchar(7) DEFAULT NULL,
                                 `slug` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lbly_comment`
--

CREATE TABLE `lbly_comment` (
                                `id` int(11) NOT NULL,
                                `user_email` varchar(320) NOT NULL,
                                `book_id` int(11) NOT NULL,
                                `text` text NOT NULL,
                                `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lbly_page`
--

CREATE TABLE `lbly_page` (
                             `id` int(11) NOT NULL,
                             `title` varchar(60) NOT NULL,
                             `content` text NOT NULL,
                             `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                             `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                             `createdBy` int(11) NOT NULL,
                             `slug` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lbly_user`
--

CREATE TABLE `lbly_user` (
                             `id` int(11) NOT NULL,
                             `firstname` varchar(55) NOT NULL,
                             `lastname` varchar(255) NOT NULL,
                             `email` varchar(320) NOT NULL,
                             `pwd` varchar(255) NOT NULL,
                             `country` char(2) NOT NULL DEFAULT 'fr',
                             `status` tinyint(4) NOT NULL DEFAULT '0',
                             `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                             `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                             `token` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `lbly_article`
--
ALTER TABLE `lbly_article`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `lbly_books`
--
ALTER TABLE `lbly_books`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `lbly_category`
--
ALTER TABLE `lbly_category`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `lbly_comment`
--
ALTER TABLE `lbly_comment`
    ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Index pour la table `lbly_page`
--
ALTER TABLE `lbly_page`
    ADD PRIMARY KEY (`id`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Index pour la table `lbly_user`
--
ALTER TABLE `lbly_user`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `lbly_article`
--
ALTER TABLE `lbly_article`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lbly_books`
--
ALTER TABLE `lbly_books`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lbly_category`
--
ALTER TABLE `lbly_category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lbly_comment`
--
ALTER TABLE `lbly_comment`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lbly_page`
--
ALTER TABLE `lbly_page`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lbly_user`
--
ALTER TABLE `lbly_user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lbly_comment`
--
ALTER TABLE `lbly_comment`
    ADD CONSTRAINT `fk_book_id` FOREIGN KEY (`book_id`) REFERENCES `lbly_books` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lbly_page`
--
ALTER TABLE `lbly_page`
    ADD CONSTRAINT `lbly_page_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `lbly_user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
