-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : database
-- Généré le : jeu. 29 avr. 2021 à 15:37
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
-- Structure de la table `lbly_page`
--

CREATE TABLE `lbly_page` (
    `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `title` varchar(60) NOT NULL,
    `content` text NOT NULL,
    `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `createdBy` int NOT NULL,
    `slug` varchar(70) NOT NULL,
    FOREIGN KEY (createdBy) REFERENCES lbly_user(id)
);