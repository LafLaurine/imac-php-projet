-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 22 mai 2019 à 21:19
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `spottimac`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int(15) NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(80) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'imac'),
(2, 'projets_perso'),
(3, 'aide'),
(4, 'interets'),
(5, 'divers');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(15) NOT NULL AUTO_INCREMENT,
  `date_commentaire` date NOT NULL,
  `id_publication` int(15) NOT NULL,
  `id_user` int(15) NOT NULL,
  `content_com` varchar(1500) NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `fk_publi` (`id_publication`),
  KEY `fk_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `date_commentaire`, `id_publication`, `id_user`, `content_com`) VALUES
(1, '2019-05-04', 1, 2, 'Non désolée je n\'y arrive pas non plus'),
(2, '2019-05-02', 2, 1, 'Oui ! il faut ça et ça et ça'),
(3, '2019-05-04', 1, 1, 'Oui, va voir ce lien :'),
(4, '2019-05-04', 1, 1, 'dffsf'),
(5, '2019-05-06', 1, 3, 'fdsfsfs'),
(6, '2019-05-07', 1, 3, 'dfdsfds'),
(7, '2019-05-10', 4, 3, 'ghjghj'),
(8, '2019-05-10', 1, 3, 'j\'adore ce que vous faites'),
(9, '2019-05-11', 6, 3, 'BONJOUR'),
(10, '2019-05-13', 1, 3, 'FOR WHOM THE BELL');

-- --------------------------------------------------------

--
-- Structure de la table `publication`
--

DROP TABLE IF EXISTS `publication`;
CREATE TABLE IF NOT EXISTS `publication` (
  `id_publication` int(15) NOT NULL AUTO_INCREMENT,
  `titre_publication` varchar(150) NOT NULL,
  `date_publication` date NOT NULL,
  `id_topic` int(15) NOT NULL,
  `id_user` int(15) NOT NULL,
  `content` varchar(1500) NOT NULL,
  `lien_fichier` text,
  PRIMARY KEY (`id_publication`),
  KEY `fk_topic` (`id_topic`),
  KEY `fk_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `publication`
--

INSERT INTO `publication` (`id_publication`, `titre_publication`, `date_publication`, `id_topic`, `id_user`, `content`, `lien_fichier`) VALUES
(1, 'Aide Tower Defense', '2019-05-04', 7, 1, 'Coucou ! Je ne comprends pas l\'algo de Bressenham, quelqu\'un peut-il m\'expliquer ?', 'https://images.assetsdelivery.com/compings_v2/ornitopter/ornitopter1509/ornitopter150900301.jpg'),
(2, 'Projet son', '2019-04-25', 5, 2, 'salut ! qqn sait quel matériel il faut ?', NULL),
(3, 'BONSOIR LAURINE', '2019-05-13', 3, 3, 'BONSOIR', 'https://positivr.fr/wp-content/uploads/2018/01/calineur-chat-veterinaire-dublin-job-annonce-une.jpg'),
(4, 'video test', '2019-05-17', 2, 2, 'TEST ANGELE', 'https://www.youtube.com/embed/HK9O2FSzBMA');

-- --------------------------------------------------------

--
-- Structure de la table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE IF NOT EXISTS `quotes` (
  `id_quotes` int(15) NOT NULL AUTO_INCREMENT,
  `content_quotes` varchar(500) NOT NULL,
  `firstname_author` varchar(500) NOT NULL,
  `lastname_author` varchar(500) NOT NULL,
  PRIMARY KEY (`id_quotes`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `quotes`
--

INSERT INTO `quotes` (`id_quotes`, `content_quotes`, `firstname_author`, `lastname_author`) VALUES
(1, 'On ne se marie pas avec une classe de CM2', 'Sylvain', 'Cherrier'),
(2, 'A la manno', 'Antoine', 'Chevreuil'),
(3, 'Si on prend comme exemple les mamifères', 'Jean-Christophe', 'Novelli'),
(4, 'Vous avez 45 minutes pour le contrôle', 'Vinceslas', 'Biri'),
(5, 'Il est encore temps d\'aller vous inscrire dans une autre formation et aller gratouiller des mygales', 'Jean-Christophe', 'Novelli'),
(6, 'Imagine que c’est toi...mais devenu fou par l’abus de LSD', 'Sylvain', 'Cherrier'),
(7, 'Quoi ?! Vous connaissez pas ?!', 'Émilie', 'Verger'),
(8, 'Il y a peut-être d\'autres Sylvain Cherrier, ce que je ne souhaite à personne au monde.', 'Sylvain', 'Cherrier'),
(9, 'Pourquoi protéger ses attributs ?', 'Sylvain', 'Cherrier'),
(10, 'Ça prend moins de temps de dire non que de dire oui', 'Jean-Christophe', 'Novelli'),
(11, 'Tout ne se résout pas avec du fric, on n’est pas aux Etats-Unis', 'Didier', 'Frochot'),
(12, 'C’est un brave nombre', 'Antoine', 'Chevreuil'),
(13, 'C’est un vieux problème...', 'Antoine', 'Chevreuil'),
(14, 'C’est la vie', 'Antoine', 'Chevreuil'),
(15, 'C’est vraiment trop fort de café', 'Antoine', 'Chevreuil'),
(16, 'L’analyse, c’est jamais très commode!', 'Antoine', 'Chevreuil'),
(17, 'Y’a rien de neuf sous le soleil', 'Antoine', 'Chevreuil'),
(18, 'Alors, le meilleur horaire pour une réunion...', 'Ettayeb', 'Tewfik'),
(19, 'Et π c\'est tout', 'Antoine', 'Chevreuil'),
(20, 'Se faire avoir comme dans un bois', 'Antoine', 'Chevreuil'),
(21, 'On le laisse à la niche (le nombre)', 'Antoine', 'Chevreuil'),
(22, 'Je saurai raccrocher les wagons', 'Antoine', 'Chevreuil'),
(23, 'Et là vous resservez la soupe !', 'Antoine', 'Chevreuil'),
(24, 'Ça compte pour peanuts ça en fait !', 'Antoine', 'Chevreuil');

-- --------------------------------------------------------

--
-- Structure de la table `topic`
--

DROP TABLE IF EXISTS `topic`;
CREATE TABLE IF NOT EXISTS `topic` (
  `id_topic` int(15) NOT NULL AUTO_INCREMENT,
  `nom_topic` varchar(150) NOT NULL,
  `id_categorie` int(15) NOT NULL,
  PRIMARY KEY (`id_topic`),
  KEY `fk_categorie` (`id_categorie`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `topic`
--

INSERT INTO `topic` (`id_topic`, `nom_topic`, `id_categorie`) VALUES
(1, 'profs', 1),
(2, 'projets', 1),
(3, 'événements', 1),
(4, 'infographie', 2),
(5, 'audio', 2),
(6, 'audiovisuel', 2),
(7, 'programmation', 2),
(8, 'personnelle', 3),
(9, 'scolaire', 3),
(10, 'cinéma', 4),
(11, 'musique', 4),
(12, 'art', 4),
(13, 'sport', 4),
(14, 'divers', 4),
(15, 'jeux_vidéos', 4);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(15) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(500) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(1, 'Lau', '9834876DCFB05CB167A5C24953EBA58C4AC89B1ADF57F28F2F9D09AF107EE8F0'),
(2, 'COUCOU', '3E744B9DC39389BAF0C5A0660589B8402F3DBB49B89B3E75F2C9355852A3C677'),
(3, 'Pocky', '1ad25d0002690dc02e2708a297d8c9df1f160d376f663309cc261c7c921367e7'),
(4, 'Bidule', 'f2d81a260dea8a100dd517984e53c56a7523d96942a834b9cdc249bd4e8c7aa9'),
(5, 'Bonsoir', '12b0f0dcaefb10c02a83aa9adb025978ddb5512dc04eb39df6811c6a6bf9770c');

-- --------------------------------------------------------

--
-- Structure de la table `user_liked`
--

DROP TABLE IF EXISTS `user_liked`;
CREATE TABLE IF NOT EXISTS `user_liked` (
  `id_publication` int(15) NOT NULL,
  `id_user` int(15) NOT NULL,
  `liked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_publication`,`id_user`),
  KEY `fk_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_liked`
--

INSERT INTO `user_liked` (`id_publication`, `id_user`, `liked`) VALUES
(2, 3, 1),
(2, 5, 1),
(1, 3, 1),
(3, 5, 1),
(4, 3, 1),
(3, 3, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
