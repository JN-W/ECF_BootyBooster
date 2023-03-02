-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: ltnya0pnki2ck9w8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com    Database: zwzf1pl9wk2cv7cc
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20221018144955','2022-10-18 16:50:19',88),('DoctrineMigrations\\Version20221019084747','2022-10-19 10:48:08',2656);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner`
--

DROP TABLE IF EXISTS `partner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_312B3E16A76ED395` (`user_id`),
  CONSTRAINT `FK_312B3E16A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner`
--

LOCK TABLES `partner` WRITE;
/*!40000 ALTER TABLE `partner` DISABLE KEYS */;
INSERT INTO `partner` VALUES (1,1,'Roubaix',1,'BootyBooster de la ville de Roubaix, gérée par M. Tartempion'),(2,1,'Dunkerque',1,'Franchisé de dunkerque'),(3,4,'Perpignan',1,'L\'incroyable franchise de Perpignan, crée en 1854, par Alfred Dubiscoto'),(4,1,'Biscarosse',0,'Pour les musclés de Biscarosse'),(12,2,'Marseille',1,'A jamais le meilleur partenaire'),(15,5,'Saint-Etienne',1,'The green life'),(17,23,'Istres',0,NULL),(18,24,'lnncqsckfeznf',0,'k,fmenzmkgzemge');
/*!40000 ALTER TABLE `partner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_service`
--

DROP TABLE IF EXISTS `partner_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_service` (
  `partner_id` int NOT NULL,
  `service_id` int NOT NULL,
  PRIMARY KEY (`partner_id`,`service_id`),
  KEY `IDX_2677F9CF9393F8FE` (`partner_id`),
  KEY `IDX_2677F9CFED5CA9E6` (`service_id`),
  CONSTRAINT `FK_2677F9CF9393F8FE` FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2677F9CFED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_service`
--

LOCK TABLES `partner_service` WRITE;
/*!40000 ALTER TABLE `partner_service` DISABLE KEYS */;
INSERT INTO `partner_service` VALUES (2,1),(4,1),(4,4),(12,1),(12,4),(15,2),(15,3),(17,1),(17,4),(18,4);
/*!40000 ALTER TABLE `partner_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'Vente de boissons fraiches'),(2,'Cours de fitness en ligne'),(3,'Costume de mascotte'),(4,'Externalisation du nettoyage');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `structure`
--

DROP TABLE IF EXISTS `structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `partner_id` int NOT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6F0137EAA76ED395` (`user_id`),
  KEY `IDX_6F0137EA9393F8FE` (`partner_id`),
  CONSTRAINT `FK_6F0137EA9393F8FE` FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`),
  CONSTRAINT `FK_6F0137EAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `structure`
--

LOCK TABLES `structure` WRITE;
/*!40000 ALTER TABLE `structure` DISABLE KEYS */;
INSERT INTO `structure` VALUES (1,2,1,'26 rue du port',1),(2,4,3,'5 rue du piment d\'espelette',1),(3,1,1,'3 rue du coin',0),(4,1,1,'6 rue du coin',0),(8,3,12,'13 rue du chien saucisse',1),(9,4,12,'13 rue du chien saucisse',1),(13,3,15,'35 place Fourneyron',1),(14,1,1,'51 allée de la grisaille',1),(15,1,3,'4 rue du brouillard',1),(16,1,12,'1 rue des 3 mages',0),(17,1,15,'place du peuple',1),(20,21,4,'13 impasse du front de mer',1),(21,1,17,'Rue de l\'olivier',1);
/*!40000 ALTER TABLE `structure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `structure_service`
--

DROP TABLE IF EXISTS `structure_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure_service` (
  `structure_id` int NOT NULL,
  `service_id` int NOT NULL,
  PRIMARY KEY (`structure_id`,`service_id`),
  KEY `IDX_3A3FEAE32534008B` (`structure_id`),
  KEY `IDX_3A3FEAE3ED5CA9E6` (`service_id`),
  CONSTRAINT `FK_3A3FEAE32534008B` FOREIGN KEY (`structure_id`) REFERENCES `structure` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3A3FEAE3ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `structure_service`
--

LOCK TABLES `structure_service` WRITE;
/*!40000 ALTER TABLE `structure_service` DISABLE KEYS */;
INSERT INTO `structure_service` VALUES (8,1),(8,4),(9,1),(9,4),(13,2),(13,3),(16,1),(16,4),(17,2),(17,3),(20,1),(20,4),(21,1),(21,2),(21,3),(21,4);
/*!40000 ALTER TABLE `structure_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Jon@test.com','[\"ROLE_USER\",\"ROLE_PARTNER\",\"ROLE_STRUCTURE\",\"ROLE_FRANCHISE\"]','$2y$13$29BR3uMXjcVbPkwd5Q6e6uOHShJpPZq49k0dAvwJGNqSPktubSwuC'),(2,'Jane@test.com','[\"ROLE_USER\",\"ROLE_PARTNER\",\"ROLE_STRUCTURE\"]','$2y$13$8ym.Se8AdKZTOVKuD7XEKORhGDGEbQcr/HpHXBTVJXnttLb0Zndb2'),(3,'Jin@test.com','[\"ROLE_USER\",\"ROLE_PARTNER\",\"ROLE_STRUCTURE\"]','$2y$13$.E5ERyOETLvV0r2W7SDLuukjLT01C/WHI5H0qYfeqTujRxAi3v6i.'),(4,'toto@mail.com','[\"ROLE_USER\",\"ROLE_PARTNER\",\"ROLE_STRUCTURE\"]','$2y$13$fybKjRAQrt8mb1D6ecrf6.0Ywr6RGh4vbO8hjv/PDKRSYSYcdrJLa'),(5,'Jun@test.com','[\"ROLE_USER\",\"ROLE_PARTNER\"]','$2y$13$ELe0ZUwPSKySgDPQ18f0..WEL9NMvhR3sz/1keGhJXfgXOQCasUCq'),(6,'Jen@exemple.com','[]','$2y$13$VM8m/9wgYEtA/zHQq3UfcubJoqrUn.1amhiayL7BplNX.RkezfLTi'),(7,'Joun@exemple.com','[]','$2y$13$GKFJZRp7LFDK2F4APpkTL.B1X/wULi6ZO1yGjOrhGmhPOCm/Ih32K'),(8,'Jina@test.com','[]','$2y$13$D12PNBX7ksUlznQAaYZHQ.PWqBO089C0rW04aN.e4vOMUhwXekv3m'),(9,'Jina2@test.com','[]','$2y$13$wHQULblAyC2Id1NMQ9a3UO6yOjN1BDqX28tJFlrIGX0NPpu/Nb1Yu'),(10,'Jina3@test.com','[]','$2y$13$5WFVIe7h1IlVOCRPnvgn1.U5JtnktM0OvUYSx5Ge/SLoBksJ979m.'),(11,'Jina4@test.com','[]','$2y$13$6NZBL.95PwI0/VqXYXQdaOrSI/5bDE.KKYMy71Tq349u5Ef87YwJu'),(12,'Jina5@test.com','[]','$2y$13$Ylqf54vPUvftNiF6cjC1C.hrwVkrcSj2nVSiN22ioKrU15zsm82uC'),(13,'Jina6@test.com','[]','$2y$13$iUb0VPUpt1GTc30F5CI3xeupuLGX0F7THOz5uZOBhJLZDqqh2OYFW'),(14,'Jina7@test.com','[]','$2y$13$cbS4jmg2mNMzK7jTt52R6ue7BtROeKKXm1dFeHVfytFK7oXcvFvWC'),(15,'Jina8@test.com','[]','$2y$13$RmHheSOAEyHshKv5rGvAPOAddK0Zd2/rXX4dh9/WJwji556./SzRa'),(16,'Jina9@mail.com','[]','$2y$13$yLN1fo.fjvixAEJL/I.XieKRd/w3WD4xGebS4nU8jCo77u2tNIZWm'),(17,'Jina10@mail.com','[]','$2y$13$FGBLgR5DLEovBgG3EC6TT.Ie/97FTWkxBosIbB16eXXWae/TNR5J2'),(18,'Jina11@mail.com','[]','$2y$13$uUfFKTFJP29oFuMl74O5bOJskN1jCJ53g9bp0ocFo7/IpMyhI2.0m'),(19,'Jina12@exemple.com','[]','$2y$13$WXOqxaiNJ5ZduDSUhNXTVeZS/ruKYpaIMA4LyQ6aZ8Cg8Fk7mxT5S'),(20,'Gege10@test.com','[\"ROLE_USER\",\"ROLE_PARTNER\",\"ROLE_STRUCTURE\"]','$2y$13$Iu56PTafW0En13MQhBOa/.Iq9Yderd7UFEbrKusjAhkZZjD95IhCe'),(21,'Gege21@test.com','[\"ROLE_USER\",\"ROLE_STRUCTURE\"]','$2y$13$WadgLK8DW/rdisCr9zTg7.RQKpHwzsvcnqW/hoeP.UWox6JZdlGlq'),(22,'Gege31@test.com','[]','$2y$13$gemJQwuoPrrFbbAKFzurDeV6M44uzl7pxvEXxEscSx9qEw0m0wij6'),(23,'gege35@test.com','[\"ROLE_USER\",\"ROLE_PARTNER\"]','$2y$13$Kvgsd3M/zS.wgXol/dqd9.dlGMP06LQO3xGfBXaRNQCC1JQe6U47a'),(24,'azfdnolaj@fzezf.fr','[\"ROLE_USER\",\"ROLE_PARTNER\"]','$2y$13$YiwbsiWTI6ISZQ9WMyqXh.CB4GwHX6/R7WXq0sm88WsHECFZErcXy');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-24 16:05:30
