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
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(15) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(500) NOT NULL,
  CONSTRAINT pk_user PRIMARY KEY (id_user)
);

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS  `categories` (
    `id_categorie` int(15) NOT NULL AUTO_INCREMENT,
    `nom_categorie` varchar(80) NOT NULL,
    CONSTRAINT pk_categorie PRIMARY KEY (id_categorie)
);

DROP TABLE IF EXISTS `topic`;
CREATE TABLE IF NOT EXISTS `topic`  (
  `id_topic` int(15) NOT NULL AUTO_INCREMENT,
  `nom_topic` varchar(150) NOT NULL,
  `id_categorie` int(15) NOT NULL,
  CONSTRAINT pk_topic PRIMARY KEY (id_topic),
  CONSTRAINT fk_categorie FOREIGN KEY (id_categorie)
  REFERENCES categorie(id_categorie)
);

DROP TABLE IF EXISTS `publication`;
CREATE TABLE IF NOT EXISTS `publication` (
  `id_publication` int(15) NOT NULL AUTO_INCREMENT,
  `titre_publication` varchar(150) NOT NULL,
  `date_publication` DATE NOT NULL,
  `id_topic` int(15) NOT NULL,
  `id_user` int(15) NOT NULL,
  `content` varchar(1500) NOT NULL,
  `lien_fichier` text,
  CONSTRAINT pk_publication PRIMARY KEY (id_publication),
  CONSTRAINT fk_topic FOREIGN KEY (id_topic)
  REFERENCES topic(id_topic),
  CONSTRAINT fk_user FOREIGN KEY (id_user)
  REFERENCES user(id_user)
);

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(15) NOT NULL AUTO_INCREMENT,
  `date_commentaire` DATE NOT NULL,
  `id_publication` int(15) NOT NULL,
  `id_user` int(15) NOT NULL,
  `content_com` varchar(1500) NOT NULL,
  CONSTRAINT pk_commentaire PRIMARY KEY (id_commentaire),
  CONSTRAINT fk_publi FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication),
  CONSTRAINT fk_user FOREIGN KEY (id_user)
  REFERENCES user(id_user)
);

DROP TABLE IF EXISTS `user_liked`;
CREATE TABLE IF NOT EXISTS `user_liked` (
  `id_publication` int(15) NOT NULL,
  `id_user` int(15) NOT NULL,
  `liked` tinyint(1) NOT NULL DEFAULT '0',
  CONSTRAINT pk_like_publi PRIMARY KEY (id_publication,id_user),
  CONSTRAINT fk_publi FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication),
  CONSTRAINT fk_user FOREIGN KEY (id_user)
  REFERENCES user(id_user)
);

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE IF NOT EXISTS  `quotes` (
    `id_quotes` int(15) NOT NULL AUTO_INCREMENT,
    `content_quotes` varchar(500) NOT NULL,
    `firstname_author` varchar(500) NOT NULL,
    `lastname_author` varchar(500) NOT NULL,
    CONSTRAINT pk_quotes PRIMARY KEY (id_quotes)
);

INSERT INTO `quotes` (`id_quotes`, `content_quotes`, `firstname_author`,`lastname_author`) VALUES
(1, 'On ne se marie pas avec une classe de CM2', 'Sylvain','Cherrier'),
(2, 'A la mano','Antoine','Chevreuil'),
(3, 'Si on prend comme exemple les mamifères','Jean-Christophe','Novelli'),
(4, 'Vous avez 45 minutes pour le contrôle','Vinceslas','Biri'),
(5, "Il est encore temps d'aller vous inscrire dans une autre formation et aller gratouiller des mygales",'Jean-Christophe','Novelli'),
(6, "Imagine que c’est toi...mais devenu fou par l’abus de LSD",'Sylvain','Cherrier'),
(7, "Quoi ?! Vous connaissez pas ?!",'Émilie','Verger'),
(8, "Il y a peut-être d'autres Sylvain Cherrier, ce que je ne souhaite à personne au monde.",'Sylvain','Cherrier'),
(9, "Pourquoi protéger ses attributs ?",'Sylvain','Cherrier'),
(10, "Ça prend moins de temps de dire non que de dire oui",'Jean-Christophe','Novelli'),
(11, "Tout ne se résout pas avec du fric, on n’est pas aux Etats-Unis",'Didier','Frochot'),
(12,"C’est un brave nombre",'Antoine','Chevreuil'),
(13,"C’est un vieux problème...",'Antoine','Chevreuil'),
(14,"C’est la vie",'Antoine','Chevreuil'),
(15,"C’est vraiment trop fort de café",'Antoine','Chevreuil'),
(16,"L’analyse, c’est jamais très commode!",'Antoine','Chevreuil'),
(17,"Y’a rien de neuf sous le soleil",'Antoine','Chevreuil'),
(18,"Alors, le meilleur horaire pour une réunion...",'Ettayeb','Tewfik'),
(19,"Et π c'est tout",'Antoine','Chevreuil'),
(20,"Se faire avoir comme dans un bois",'Antoine','Chevreuil'),
(21,"On le laisse à la niche (le nombre)",'Antoine','Chevreuil'),
(22,"Je saurai raccrocher les wagons",'Antoine','Chevreuil'),
(23,"Et là vous resservez la soupe !",'Antoine','Chevreuil'),
(24,"Ça compte pour peanuts ça en fait !",'Antoine','Chevreuil');

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, ' iMAC'),
(2, 'Projets perso'),
(3, 'Aide'),
(4, 'Intérêts'),
(5, 'divers');

INSERT INTO `topic` (`id_topic`, `nom_topic`,`id_categorie`) VALUES
(1, 'Profs',1),
(2, 'Projets',1),
(3, 'Événements',1),
(4, 'Infographie',2),
(5, 'Audio',2),
(6, 'Audiovisuel',2),
(7, 'Programmation',2),
(8, 'Personnelle',3),
(9, 'Scolaire',3),
(10, 'Cinéma',4),
(11, 'Musique',4),
(12, 'Art',4),
(13, 'Sport',4),
(14, 'Divers',4),
(15, 'Jeux vidéos',4);

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(1, 'Lau', '9834876DCFB05CB167A5C24953EBA58C4AC89B1ADF57F28F2F9D09AF107EE8F0'),
(2, 'COUCOU', '3E744B9DC39389BAF0C5A0660589B8402F3DBB49B89B3E75F2C9355852A3C677'),
(3, 'Pocky', '1ad25d0002690dc02e2708a297d8c9df1f160d376f663309cc261c7c921367e7'),
(4, 'Bidule', 'f2d81a260dea8a100dd517984e53c56a7523d96942a834b9cdc249bd4e8c7aa9'),
(5, 'Bonsoir', '12b0f0dcaefb10c02a83aa9adb025978ddb5512dc04eb39df6811c6a6bf9770c');

INSERT INTO `user_liked` (`id_publication`, `id_user`, `liked`) VALUES
(2, 3, 1),
(2, 5, 1),
(1, 3, 1),
(3, 5, 1),
(4, 3, 1),
(3, 3, 1);

INSERT INTO `publication` (`id_publication`, `titre_publication`, `date_publication`, `id_topic`, `id_user`, `content`, `lien_fichier`) VALUES
(1,  'Projet tower defense', '2019-04-12', 9, 1, 'Bonsoir à tous ! Ça va faire deux semaines que je bloque sur l’algorithme de Bressenham, j’en peux vraiment plus ! Si certains pouvaient me l’expliquer, j’en serais ravie !', NULL),
(2,  'Question sur Unity (C#)', '2019-03-05', 8, 1, 'Hello la populace ! Y’en a qui s’y connaisse à Unity ? J’ai commencé à modéliser une interface pour un petit jeu et j’ai commencé à implémenter la partie algorithmie. Est-ce que vous connaissez de bons tutoriels de C# pour que je puisse créer un bon jeu ? Et si jamais, est-ce que certains peuvent m’expliquer les pires erreurs à ne pas faire ?', NULL),
(3,  'The Coding Train', '2019-03-30', 9, 1, 'Hello les petits poulets ! Je ne sais pas si vous connaissez The Coding Train, mais c’est genre LE youtubeur qu’il vous faut. Il explique tout pleins de trucs par rapport à la programmation, surtout par rapport au processing. Je vous mets un exemple en vidéo ! :)', 'https://www.youtube.com/embed/KkyIDI6rQJI'),
(4,  'Danse des fonctions', '2019-04-04', 9, 1, 'Petit rappel sympathique pour tracer les graphes des fonctions !', 'https://pbs.twimg.com/media/DgsNWKOXUAAOOrq.jpg'),
(5,  'Mood du jour', '2019-05-04', 16, 2, 'Mon âme flotte au dessus de moi', 'https://images.ecosia.org/TDynNwICUkpSFUIhS97gbYhyqsY=/0x390/smart/http%3A%2F%2Fwww.fanartreview.com%2Ffarusr%2F2%2Fw-1296831-466709.jpg'),
(6,  'Top histoires insolites', '2019-05-21', 17, 2, 'Top histoires insolites', 'https://www.histoiresinsolites.com/'),
(7,  'VIVE LES CHATS', '2019-05-12', 18, 2, 'J’AIME LES CHATS', 'https://www.youtube.com/watch?v=b9f4J7jWHHg'),
(8,  'I am going to sink', '2019-05-16', 16, 2, 'Projets, cours, projets, cours…. AAAAAAAAAAAAAHhhhhhhhhhhhhhhhh', NULL),
(9,  'Découverte', '2019-05-16', 17, 2, 'Le mystère des peintures cachées de l’U4', 'https://www.francebleu.fr/infos/insolite/decouverte-d-une-fresque-insolite-dans-les-vestiaires-de-l-u4-le-mystere-est-leve-1556638714'),
(10, 'De mystérieuses découvertes', '2019-04-16', 17, 2, 'En me baladant sur le net, j’ai trouvé des reliques! Je me devais de vous les partager', 'https://dailygeekshow.com/6-decouvertes-mysterieuses-et-epatantes-que-la-science-narrive-toujours-pas-a-expliquer-aujourdhui/'),
(11, 'De mystérieuses découvertes 2', '2019-04-26', 18, 2, 'J’espère que vous kifferez autant que moi', 'https://www.youtube.com/watch?v=f47eID9xjlI'),
(12, 'Sortie Expo', '2019-04-30', 3, 3, 'Salut les IMAC’s ! Samedi je propose d’aller voir l’expo de Toutankhamon aux halles de la Villette, qui est partant ? ', 'https://7alyon.com/wp-content/uploads/2018/11/toutankhamon-exposition-paris.jpg'),
(13, 'Barbecue', '2019-05-17', 3, 3, 'Comme vous le savez tous et toutes le 29 mai aura lieu le barbecue IMAC, alors si vous avez des idées d’animations pour la soirée n’hésitez pas à les partager :)', 'https://media.giphy.com/media/2P5ild0Cj93SU/giphy.gif'),
(14, 'Race', '2019-05-06', 3, 3, 'Salut les IMAC’s je propose une méga race après tous les partiels !!', 'https://media.giphy.com/media/FUlVlkj27PttK/giphy.gif'),
(15, 'Mail', '2019-04-28', 1, 3, 'Hello ! Quelqu’un à le mail de Robillard svp ?', NULL),
(16, 'Cours signal', '2019-05-10', 1, 3, 'Est ce que c’est normal que le prof de signal déplace ses cours à chaque fois ? Ça commence à devenir un peu relou…', 'https://media.giphy.com/media/YA6dmVW0gfIw8/giphy.gif'),
(17, 'Anglais', '2019-05-10', 1, 3, 'C’est moi ou le prof d’Anglais fait tout sauf nous faire un vrai cours ?', 'https://media.giphy.com/media/3orif0olmGNHtrBnXO/giphy.gif'),
(18, 'Tower Defense', '2019-05-20', 2, 3, 'Salut ! J’ai un problème avec la carte itd de mon TowerDefense, est ce que quelqu’un pourrait m’aidez svp ?', 'https://msdnshared.blob.core.windows.net/media/2016/03/debugging-all-up.png'),
(19, 'After Effects', '2019-03-25', 2, 3, 'Coucou les IMAC ! J’ai un problème avec AE sur mon ordi, qqun à le crack 2019 ?', 'https://media.giphy.com/media/PK5CQPd6rCF3y/giphy.gif'),
(20, 'Communication exposé', '2019-05-16', 2, 3, 'Hello ! Vous savez si on va vraiment tous passer pour les exposés en com ? Parce que j’ai vraiment la flemme…', 'https://media.giphy.com/media/8EmeieJAGjvUI/giphy.gif'),
(21, 'Broderie Spatiale', '2019-05-01', 14, 4, 'Pour tous les fans d’espace mais aussi de broderie, voici une idée pour personnaliser vos vêtements!', 'https://i.pinimg.com/564x/7d/65/63/7d65631fe1c3f6e3f37bfa1785e74225.jpg'),
(22, 'Endgame', '2019-05-19', 10, 4, 'Je n’ai toujours pas vu Avengers Endgame… Est ce que ça vous dit d’aller le voir (ou même le revoir) dans la semaine ? Tenez moi au courant. :)', NULL),
(23, 'Sortie Mortal Kombat 11', '2019-05-08', 15, 4, 'Hello vous, j’espère que vous allez bien. Le jeu Mortal Kombat 11 est sorti y’a pas longtemps, et je pensais que ça pouvait être intéressant qu’on en parle en commentaire, ça vous tente? :) ', 'https://www.youtube.com/watch?v=39cburdHXTM&feature=youtu.be'),
(24, 'Mon cover', '2019-05-08', 11, 4, 'Hello, j’ai fais cette petite cover y’a pas longtemps et j’aimerai avoir des avis. Ça change un peu de l’originale et j’espère que ça vous plaira.', 'https://www.youtube.com/watch?v=9CH0vUflB4E'),
(25, 'Petit foot tranquille', '2019-05-04', 13, 4, 'Les beaux jours reviennent, ça tente quelques uns d’entre vous un petit foot pour décompresser ? Bisous les poulets', NULL),
(26, 'Votre film préféré', '2019-05-06', 10, 4, 'Juste une petite question : quel est votre film préféré ? Celui que vous pourriez regarder sans jamais vous lasser. \r\nPerso ça serait Gatsby avec DiCaprio. Je vous mets la bande d’annonce si jamais vous ne l’avez pas encore vu. ', 'https://www.youtube.com/watch?v=3DZBGR0vP8I'),
(27, 'Votre morceau préféré', '2019-05-06', 11, 4, ' Bon j’avoue j’ai vu cette question dans le topic cinéma au sujet des films, mais je trouvais intéressant de savoir quel est votre morceau préféré. J’ai hâte de voir vos réponses..\r\nLove les imacs', NULL),
(28, 'Youtube en ce moment', '2019-05-12', 14, 4, 'Je trouve que les vidéos sur youtube sont de moins en moins bien, c’est toujours les mêmes choses et je commence à m’en lasser.. Vous en pensez quoi vous du tournent que c’est en train de prendre ? \r\nAprès peut-être que je ne suis pas abonnée aux bonnes chaînes, si vous en avez à me proposer je suis carrément preneuse.', NULL),
(29, 'Un peu de nostalgie', '2019-05-06', 15, 4, 'Juste une petite question : quel est votre film préféré ? Celui que vous pourriez regarder sans jamais vous lasser. \r\nPerso ça serait Gatsby avec DiCaprio. Je vous mets la bande d’annonce si jamais vous ne l’avez pas encore vu. ', 'https://www.youtube.com/watch?v=NBKqJi0jkms'),
(30, 'Expo Van Gogh à l’atelier des Lumières', '2019-04-19', 12, 4, 'Hola,\r\nJe reviens tout juste de l’exposition Van Gogh à l’atelier des Lumières et c’était vraiment ouf. On a vraiment l’impression d’être projeté dans les tableaux. Entre la musique et toutes les couleurs on se sent comme dans une bulle. C’était un moment magique je vous recommande à 1000%.\r\n(Par contre essayez de vous y prendre à l’avance pour réserver vos billets, il faut choisir son créneau sur le site.)', 'https://img-4.linternaute.com/0R8jYaP9pjj06dirOXL6b7sgdZo=/620x/smart/d54954bcd4b7425ba29b4a8290d7159c/ccmcms-linternaute/11054666.jpg'),
(31, 'Nuit des musées', '2019-05-13', 12, 4, 'Hey, c’est la nuit des musée ce samedi ça vous dit d’y aller ? On pourrait se faire un petit marathon de musée, ça peut être très cool.', NULL),
(32, 'Dessins de personnages de film', '2019-03-14', 6, 5, 'Reconnaîtriez-vous le film à partir de ces petits dessins? ^^', 'https://i.pinimg.com/564x/19/b0/70/19b070cc5cc7648f003bc9c167be8ee7.jpg'),
(33, 'Cherche partenaires pour projet', '2019-04-25', 5, 5, 'Salut ! Je suis en train de réfléchir à un projet audio et j’aimerais savoir si des personnes étaient intéressées pour le faire avec moi. Ce serait un projet autour de la création de musiques, où chacun pourrait jouer de son instrument. Si des personnes sont intéressées, n’hésitez pas à me le dire en commentaire!', NULL),
(34, 'Histoire sonore', '2019-04-28', 5, 5, 'Bonjour! Je suis en train de réaliser une histoire sonore autour du conte du petit chaperon rouge, vous auriez des idées pour les différents bruitages qu’il pourrait y avoir?', NULL),
(35, 'Programmation Tetris', '2019-05-07', 7, 5, 'Hey! J’ai programmé un tetris pendant les vacances! n’hésitez pas à le tester et à dire ce que vous en pensez!', 'https://www.jeux-gratuits.com/jeu-tetris.html'),
(36, 'Avis Logo', '2019-05-17', 4, 5, 'Vous avez-vu! Le logo de France Info a changé il y a pas longtemps et je le trouve cool! Dites-moi ce que vous en pensez!', 'http://img.over-blog-kiwi.com/0/87/49/59/20190125/ob_fcbf76_france-info.jpg'),
(37, 'Film super', '2019-04-13', 6, 5, 'Bonjour tout le monde! J’ai vu un super film hier et je voulais vous partager ça. Le film s’appelle Pulp Fiction, je vous le conseille!', NULL),
(38, 'Conseils tablette graphique', '2019-04-22', 4, 5, 'Hello les Imaciens/Imaciennes ! J’aimerais me mettre au digital painting et donc je voulais m’acheter une tablette graphique. Auriez-vous des conseils à me donner ?\r\n', NULL),
(39, 'Réflexion autour d’une affiche', '2019-04-22', 4, 5, 'Bonjour! J’ai découvert cette affiche l’autre jour en fouillant sur internet et j’aimerais bien savoir ce que vous en pensez. Je la trouve super mais je ne sais pas vraiment quel sens elle veut procurer. Dites-moi vos ressentis! ;)', 'https://i.pinimg.com/564x/e8/fc/3c/e8fc3c5d620afd79f52dea63558e6a0b.jpg');
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
