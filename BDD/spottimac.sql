CREATE TABLE `categories` (
    `id_categorie` int(15) NOT NULL AUTO_INCREMENT,
    `nom_categorie` varchar(80) NOT NULL,
    CONSTRAINT pk_categorie PRIMARY KEY (id_categorie)
);

CREATE TABLE `topic` (
  `id_topic` int(15) NOT NULL AUTO_INCREMENT,
  `nom_topic` varchar(150) NOT NULL,
  `id_categorie` int(15) NOT NULL,
  CONSTRAINT pk_topic PRIMARY KEY (id_topic),
  CONSTRAINT fk_categorie FOREIGN KEY (id_categorie)
  REFERENCES categorie(id_categorie)
);

CREATE TABLE `publication` (
  `id_publication` int(15) NOT NULL AUTO_INCREMENT,
  `titre_publication` varchar(150) NOT NULL,
  `date_publication` DATE NOT NULL,
  `id_topic` int(15) NOT NULL,
  CONSTRAINT pk_publication PRIMARY KEY (id_publication),
  CONSTRAINT fk_topic FOREIGN KEY (id_topic)
  REFERENCES topic(id_topic)
);

CREATE TABLE `type` (
  `id_type` int(15) NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(50) NOT NULL,
  CONSTRAINT pk_type PRIMARY KEY (id_type)
);

CREATE TABLE `type_publication` (
  `id_publication` int(15) NOT NULL,
  `id_type` int(15) NOT NULL,
  CONSTRAINT pk_type_publication PRIMARY KEY (id_publication, id_type)
);

CREATE TABLE `commentaire` (
  `id_commentaire` int(15) NOT NULL AUTO_INCREMENT,
  `date_commentaire` DATE NOT NULL,
  `id_publication` int(15) NOT NULL,
  CONSTRAINT pk_commentaire PRIMARY KEY (id_commentaire),
  CONSTRAINT fk_publi FOREIGN KEY (id_publication)
  REFERENCES publication(id_publication)
);