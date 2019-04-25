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
  `content` varchar(1500) NOT NULL,
  CONSTRAINT pk_publication PRIMARY KEY (id_publication),
  CONSTRAINT fk_topic FOREIGN KEY (id_topic)
  REFERENCES topic(id_topic)
);

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id_image` int(15) NOT NULL,
  `id_publication` int(15) NOT NULL,
  `nom_image` varchar(150) NOT NULL,
  `taille_image` INT NOT NULL,
  `type_image` varchar(50) NOT NULL,
  `desc_image` varchar(200) NOT NULL,
  `blob_image` TEXT NOT NULL,
  CONSTRAINT pk_image PRIMARY KEY (id_image),
  CONSTRAINT fk_publication FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);


DROP TABLE IF EXISTS `son`;
CREATE TABLE IF NOT EXISTS `son` (
  `id_son` int(15) NOT NULL,
  `id_publication` int(15) NOT NULL,
  `nom_son` varchar(150) NOT NULL,
  `taille_son` INT NOT NULL,
  `type_son` varchar(50) NOT NULL,
  `desc_son` varchar(200) NOT NULL,
  `blob_son` TEXT NOT NULL,
  CONSTRAINT pk_son PRIMARY KEY (id_son),
  CONSTRAINT fk_publication FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);

DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video` (
  `id_video` int(15) NOT NULL,
  `id_publication` int(15) NOT NULL,
  `nom_video` varchar(150) NOT NULL,
  `taille_video` INT NOT NULL,
  `type_video` varchar(50) NOT NULL,
  `desc_video` varchar(200) NOT NULL,
  `blob_video` TEXT NOT NULL,
  CONSTRAINT pk_video PRIMARY KEY (id_video),
  CONSTRAINT fk_publication FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(15) NOT NULL AUTO_INCREMENT,
  `date_commentaire` DATE NOT NULL,
  `id_publication` int(15) NOT NULL,
  `content_com` varchar(1500) NOT NULL,
  CONSTRAINT pk_commentaire PRIMARY KEY (id_commentaire),
  CONSTRAINT fk_publi FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);

DROP TABLE IF EXISTS `like`;
CREATE TABLE IF NOT EXISTS `like` (
  `id_like` int(15) NOT NULL AUTO_INCREMENT,
  `id_publication` int(15) NOT NULL,
  `count_like` INT,
  CONSTRAINT pk_like PRIMARY KEY (id_like),
  CONSTRAINT fk_publi FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);

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