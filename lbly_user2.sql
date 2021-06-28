-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : database
-- Généré le : lun. 28 juin 2021 à 09:02
-- Version du serveur :  5.7.32
-- Version de PHP : 7.4.11

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
-- Déchargement des données de la table `lbly_user`
--

INSERT INTO `lbly_user` (`id`, `firstname`, `lastname`, `email`, `pwd`, `country`, `status`, `createdAt`, `updatedAt`, `token`) VALUES
(1, 'bonjour', 'bonjour', 'bonjour@mail', '$2y$10$M4tZjDMtgB5w5DbpTyO8KOtCEM/lAEePILi9W12IZ/Nj.srwB/5q6', 'fr', 5, '2021-06-06 15:26:25', '2021-06-26 11:42:27', 'ee581a7c43'),
(2, 'bonsoire', 'bonsoire', 'bonsoir@mail', '$2y$10$u8Kvm48mGwkV7jVWCze88OETpuXvmHT4glBcJS829WN1VNlQOvY8y', 'fr', 0, '2021-06-06 15:49:53', NULL, ''),
(8, 'yes', 'yes', 'huit@mail', '$2y$10$lYhcp5hmhm2s6UAsKF7q2upNLX3KfEKtkX4vBewI03VPA8ITsaeRC', 'fr', 0, '2021-06-06 16:32:30', NULL, ''),
(41, 'jed', 'jed', 'jedh-jed@hotmail.fr', '$2y$10$XCtjqBlG97b1f.kzNAocseF6cd0AuGHBZQ.dU6pH.uC9wIFkUQtsO', 'fr', 4, '2021-06-27 18:03:03', '2021-06-27 18:03:38', 'd1022f15de'),
(42, 'qupoi', 'quoi', 'quoi@mail', '$2y$10$kqA0OHOLuqARDSNxYFB1kek5fajS8HjwLhOi4Rks0wfftrDf1nSle', 'fr', 0, '2021-06-27 18:04:39', NULL, 'aed020e119');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `lbly_user`
--
ALTER TABLE `lbly_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `lbly_user`
--
ALTER TABLE `lbly_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
