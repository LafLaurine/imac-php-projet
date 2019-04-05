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

DROP TABLE IF EXISTS `publication`
CREATE TABLE IF NOT EXISTS `publication` (
  `id_publication` int(15) NOT NULL AUTO_INCREMENT,
  `titre_publication` varchar(150) NOT NULL,
  `date_publication` DATE NOT NULL,
  `id_topic` int(15) NOT NULL,
  CONSTRAINT pk_publication PRIMARY KEY (id_publication),
  CONSTRAINT fk_topic FOREIGN KEY (id_topic)
  REFERENCES topic(id_topic)
);

DROP TABLE IF EXISTS `type`
CREATE TABLE IF NOT EXISTS `type` (
  `id_type` int(15) NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(50) NOT NULL,
  CONSTRAINT pk_type PRIMARY KEY (id_type)
);

DROP TABLE IF EXISTS `type_publication`
CREATE TABLE IF NOT EXISTS `type_publication` (
  `id_publication` int(15) NOT NULL,
  `id_type` int(15) NOT NULL,
  CONSTRAINT pk_type_publication PRIMARY KEY (id_publication, id_type)
);

DROP TABLE IF EXISTS `commentaire`
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(15) NOT NULL AUTO_INCREMENT,
  `date_commentaire` DATE NOT NULL,
  `id_publication` int(15) NOT NULL,
  CONSTRAINT pk_commentaire PRIMARY KEY (id_commentaire),
  CONSTRAINT fk_publi FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'imac'),
(2, 'projets_perso'),
(3, 'aide'),
(5, 'interets');

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

INSERT INTO `type` (`id_type`, `nom_style`) VALUES
(1, 'image'),
(2, 'vidéo'),
(3, 'son');