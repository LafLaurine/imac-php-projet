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
(2, 'A la manno','Antoine','Chevreuil'),
(3, 'Si on prend comme exemple les mamifères','Jean-Christophe','Novelli'),
(4, 'Vous avez 45 minutes pour le contrôle','Vinceslas','Biri'),
(5, "Il est encore temps d'aller vous inscrire dans une autre formation et aller gratouiller des mygales",'Jean-Christophe','Novelli'),
(6, "Imagine que c’est toi...mais devenu fou par l’abus de LSD",'Sylvain','Cherrier'),
(7, "Quoi ?! Vous connaissez pas ?!",'Émilie','Verger'),
(8, "Il y a peut-être d'autres Sylvain Cherrier, ce que je ne souhaite à personne au monde.",'Sylvain','Cherrier'),
(9, "Pourquoi protéger ses attributs ?",'Sylvain','Cherrier'),
(10, "Ça prend moins de temps de dire non que de dire oui",'Jean-Christophe','Novelli'),
(11, "Tout ne se résout pas avec du fric, on n’est pas aux Etats-Unis",'Didier','Frochot');

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'imac'),
(2, 'projets_perso'),
(3, 'aide'),
(4, 'interets'),
(5, 'divers');

INSERT INTO `topic` (`id_topic`, `nom_topic`,`id_categorie`) VALUES
(1, 'profs',1),
(2, 'projets',1),
(3, 'événements',1),
(4, 'infographie',2),
(5, 'audio',2),
(6, 'audiovisuel',2),
(7, 'programmation',2),
(8, 'personnelle',3),
(9, 'scolaire',3),
(10, 'cinéma',4),
(11, 'musique',4),
(12, 'art',4),
(13, 'sport',4),
(14, 'divers',4),
(15, 'jeux_vidéos',4);

INSERT INTO `commentaire` (`id_commentaire`, `date_commentaire`, `id_publication`, `id_user`, `content_com`) VALUES
(1, '2019-05-04', 1, 2,"Non désolée je n'y arrive pas non plus"),
(2, '2019-05-02', 2, 1, 'Oui ! il faut ça et ça et ça'),
(3, '2019-05-04', 1, 1, 'Oui, va voir ce lien :');

INSERT INTO `publication` (`id_publication`, `titre_publication`, `date_publication`, `id_topic`, `id_user`, `content`,`lien_fichier`) VALUES
(1, 'Aide Tower Defense', '2019-05-04', 7, 1, 'Coucou ! Je ne comprends pas l\'algo de Bressenham, quelqu\'un peut-il m\'expliquer ?','https://images.assetsdelivery.com/compings_v2/ornitopter/ornitopter1509/ornitopter150900301.jpg'),
(2, 'Projet son', '2019-04-25', 5, 2, 'salut ! qqn sait quel matériel il faut ?',NULL);