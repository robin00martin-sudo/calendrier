-- Base de données du projet « Calendrier »
-- Compatible MySQL 5.7+/8 et MariaDB 10.x

CREATE DATABASE IF NOT EXISTS `formation` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `formation`;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `formations`;
CREATE TABLE `formations` (
  `id`       int NOT NULL AUTO_INCREMENT,
  `intitule` varchar(255) NOT NULL,
  `niveau`   varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_formations_intitule` (`intitule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NB : le doublon « aide-soignant » (anciennement id 3) a été supprimé ;
-- les colonnes `duree` et `modalite`, jamais utilisées, ont été retirées.
INSERT INTO `formations` (`id`, `intitule`, `niveau`) VALUES
(1,  'CAP Accompagnant éducatif petite enfance', 'CAP'),
(2,  'Titre professionnel Commis de cuisine', 'Titre Professionnel'),
(4,  'BTS Services informatiques aux organisations (SISR)', 'Bac+2'),
(5,  'Titre professionnel Développeur intégrateur web', 'Titre Professionnel Niveau 5'),
(6,  'CAP Boulanger', 'CAP'),
(7,  'Titre professionnel Data Analyst', 'Titre Professionnel Niveau 6'),
(8,  'BTS Négociation et digitalisation de la relation client (NDRC)', 'Bac+2'),
(9,  'Diplôme d’État Auxiliaire de Puériculture (DEAP)', 'Diplôme d’État'),
(10, 'Diplôme d’État Accompagnant Éducatif et Social (DEAES)', 'Diplôme d’État'),
(11, 'Diplôme d’État Aide-Soignant (DEAS)', 'Diplôme d’État');
