-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: bluecore
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.29-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article_overview_elements_metadata`
--

DROP TABLE IF EXISTS `article_overview_elements_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_overview_elements_metadata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `show_from` datetime DEFAULT NULL,
  `show_to` datetime DEFAULT NULL,
  `order_by` varchar(45) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `element_id` int(10) unsigned NOT NULL,
  `number_of_results` int(10) unsigned DEFAULT NULL,
  `order_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `metadata_element` (`element_id`),
  CONSTRAINT `metadata_element` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_overview_elements_metadata`
--

LOCK TABLES `article_overview_elements_metadata` WRITE;
/*!40000 ALTER TABLE `article_overview_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `article_overview_elements_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_target_pages`
--

DROP TABLE IF EXISTS `article_target_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_target_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `element_holder_id` int(10) unsigned NOT NULL,
  `is_default` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_target_pages` (`element_holder_id`),
  CONSTRAINT `article_target_pages` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_target_pages`
--

LOCK TABLES `article_target_pages` WRITE;
/*!40000 ALTER TABLE `article_target_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `article_target_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_terms`
--

DROP TABLE IF EXISTS `article_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_terms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_terms`
--

LOCK TABLES `article_terms` WRITE;
/*!40000 ALTER TABLE `article_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `article_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` longtext,
  `image_id` int(10) unsigned DEFAULT NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  `publication_date` datetime NOT NULL,
  `target_page` int(10) unsigned DEFAULT NULL,
  `sort_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_element_holder` (`element_holder_id`),
  KEY `articles_pages` (`target_page`),
  CONSTRAINT `article_element_holder` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articles_pages` FOREIGN KEY (`target_page`) REFERENCES `element_holders` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles_element_terms`
--

DROP TABLE IF EXISTS `articles_element_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles_element_terms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `element_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `terms` (`term_id`),
  KEY `elements` (`element_id`),
  CONSTRAINT `elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `terms` FOREIGN KEY (`term_id`) REFERENCES `article_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles_element_terms`
--

LOCK TABLES `articles_element_terms` WRITE;
/*!40000 ALTER TABLE `articles_element_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles_element_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles_terms`
--

DROP TABLE IF EXISTS `articles_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles_terms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `term_terms` (`term_id`),
  KEY `art_articles` (`article_id`),
  CONSTRAINT `art_articles` FOREIGN KEY (`article_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `term_terms` FOREIGN KEY (`term_id`) REFERENCES `article_terms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles_terms`
--

LOCK TABLES `articles_terms` WRITE;
/*!40000 ALTER TABLE `articles_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_users`
--

DROP TABLE IF EXISTS `auth_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `email_address` varchar(45) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `prefix` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `uuid` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_users`
--

LOCK TABLES `auth_users` WRITE;
/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
INSERT INTO `auth_users` VALUES (2,'Developer','5c08dfd8c8ade29ff8485a8cc0409cf6','jim.pouwels@gmail.com','Jim','Pouwels','','2011-10-30 12:42:05','4ebbf2bdbc13e');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block_positions`
--

DROP TABLE IF EXISTS `block_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_positions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `explanation` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_positions`
--

LOCK TABLES `block_positions` WRITE;
/*!40000 ALTER TABLE `block_positions` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(10) unsigned DEFAULT NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `holder_block` (`element_holder_id`),
  KEY `position_block` (`position_id`),
  CONSTRAINT `holder_block` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `position_block` FOREIGN KEY (`position_id`) REFERENCES `block_positions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocks`
--

LOCK TABLES `blocks` WRITE;
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blocks_pages`
--

DROP TABLE IF EXISTS `blocks_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocks_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(10) unsigned NOT NULL,
  `block_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `blocks` (`block_id`),
  KEY `pages` (`page_id`),
  CONSTRAINT `blocks` FOREIGN KEY (`block_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pages` FOREIGN KEY (`page_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocks_pages`
--

LOCK TABLES `blocks_pages` WRITE;
/*!40000 ALTER TABLE `blocks_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) DEFAULT NULL,
  `published` tinyint(1) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `title` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `DOWNLOADS_2_USERS` (`created_by`),
  CONSTRAINT `DOWNLOADS_2_USERS` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloads`
--

LOCK TABLES `downloads` WRITE;
/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `element_holders`
--

DROP TABLE IF EXISTS `element_holders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `element_holders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10) unsigned DEFAULT NULL,
  `title` longtext,
  `published` tinyint(1) NOT NULL,
  `scope_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(10) unsigned DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `element_holder_user` (`created_by`),
  CONSTRAINT `element_holder_user` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `element_holders`
--

LOCK TABLES `element_holders` WRITE;
/*!40000 ALTER TABLE `element_holders` DISABLE KEYS */;
INSERT INTO `element_holders` VALUES (1,2,'Homepage',1,5,'2017-12-29 16:37:26',2,'ELEMENT_HOLDER_PAGE');
/*!40000 ALTER TABLE `element_holders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `element_types`
--

DROP TABLE IF EXISTS `element_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `element_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `classname` varchar(45) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `domain_object` varchar(255) NOT NULL,
  `scope_id` int(10) unsigned NOT NULL,
  `identifier` varchar(255) DEFAULT NULL,
  `system_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `element_types_scopes` (`scope_id`),
  CONSTRAINT `element_types_scopes` FOREIGN KEY (`scope_id`) REFERENCES `scopes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `element_types`
--

LOCK TABLES `element_types` WRITE;
/*!40000 ALTER TABLE `element_types` DISABLE KEYS */;
INSERT INTO `element_types` VALUES (1,'TextElement','/img/text_element.png','Paragraaf','text_element.php',1,'text_element',1),(2,'ListElement','/img/list_element.png','Lijst','list_element.php',2,'list_element',1),(3,'ImageElement','/img/image_element.png','Afbeelding','image_element.php',3,'image_element',1),(4,'DownloadElement','/img/download_element.png','Download','download_element.php',4,'download_element',1),(5,'ArticleOverviewElement','/img/article_overview_element.png','Artikel overzicht','article_overview_element.php',7,'article_overview_element',1);
/*!40000 ALTER TABLE `element_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `elements`
--

DROP TABLE IF EXISTS `elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `elements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `follow_up` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `element_holder` (`element_holder_id`),
  CONSTRAINT `element_holder` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `elements`
--

LOCK TABLES `elements` WRITE;
/*!40000 ALTER TABLE `elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friendly_urls`
--

DROP TABLE IF EXISTS `friendly_urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `friendly_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `friendly_url_to_element_holder` (`element_holder_id`),
  CONSTRAINT `friendly_url_to_element_holder` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friendly_urls`
--

LOCK TABLES `friendly_urls` WRITE;
/*!40000 ALTER TABLE `friendly_urls` DISABLE KEYS */;
INSERT INTO `friendly_urls` VALUES (1,'/homepage',1);
/*!40000 ALTER TABLE `friendly_urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_elements_metadata`
--

DROP TABLE IF EXISTS `image_elements_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_elements_metadata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned DEFAULT NULL,
  `element_id` int(10) unsigned NOT NULL,
  `alternative_text` varchar(255) DEFAULT NULL,
  `align` varchar(45) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_images` (`image_id`),
  KEY `fk_elements` (`element_id`),
  CONSTRAINT `fk_elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_images` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_elements_metadata`
--

LOCK TABLES `image_elements_metadata` WRITE;
/*!40000 ALTER TABLE `image_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `image_elements_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_labels`
--

DROP TABLE IF EXISTS `image_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_labels`
--

LOCK TABLES `image_labels` WRITE;
/*!40000 ALTER TABLE `image_labels` DISABLE KEYS */;
/*!40000 ALTER TABLE `image_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `file_name` longtext,
  `thumb_file_name` longtext,
  PRIMARY KEY (`id`),
  KEY `images_users` (`created_by`),
  CONSTRAINT `images_users` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images_labels`
--

DROP TABLE IF EXISTS `images_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `images` (`image_id`),
  KEY `labels` (`label_id`),
  CONSTRAINT `images` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `labels` FOREIGN KEY (`label_id`) REFERENCES `image_labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images_labels`
--

LOCK TABLES `images_labels` WRITE;
/*!40000 ALTER TABLE `images_labels` DISABLE KEYS */;
/*!40000 ALTER TABLE `images_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `target_address` varchar(255) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `code` varchar(45) NOT NULL,
  `target_element_holder` int(10) unsigned DEFAULT NULL,
  `parent_element_holder` int(10) unsigned NOT NULL,
  `target` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ref` (`target_element_holder`) USING BTREE,
  KEY `parent` (`parent_element_holder`),
  CONSTRAINT `parent` FOREIGN KEY (`parent_element_holder`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ref` FOREIGN KEY (`target_element_holder`) REFERENCES `element_holders` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_element_items`
--

DROP TABLE IF EXISTS `list_element_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_element_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) DEFAULT NULL,
  `indent` int(10) unsigned NOT NULL,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_list_element_items_1` (`element_id`) USING BTREE,
  CONSTRAINT `FK_list_element_items_1` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_element_items`
--

LOCK TABLES `list_element_items` WRITE;
/*!40000 ALTER TABLE `list_element_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_element_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_elements_metadata`
--

DROP TABLE IF EXISTS `list_elements_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_elements_metadata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_list_elements_metadata_1` (`element_id`),
  CONSTRAINT `FK_list_elements_metadata_1` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_elements_metadata`
--

LOCK TABLES `list_elements_metadata` WRITE;
/*!40000 ALTER TABLE `list_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_elements_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(255) NOT NULL,
  `message` longblob NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_groups`
--

DROP TABLE IF EXISTS `module_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL,
  `follow_up` int(10) unsigned NOT NULL,
  `element_group` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_groups`
--

LOCK TABLES `module_groups` WRITE;
/*!40000 ALTER TABLE `module_groups` DISABLE KEYS */;
INSERT INTO `module_groups` VALUES (2,'maintain',3,0),(3,'configure',5,0),(4,'design',4,0),(5,'insert',2,1);
/*!40000 ALTER TABLE `module_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_text_resource_identifier` varchar(255) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `module_group_id` int(10) unsigned DEFAULT NULL,
  `popup` tinyint(1) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `system_default` tinyint(1) NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'logout_module_title','/img/logout.png',1,0,'logout',1,1,'LogoutModuleVisual'),(2,'settings_module_title','/img/settings.png',3,0,'settings',1,1,'SettingsModuleVisual'),(3,'pages_module_title','/img/page.png',2,0,'pages',1,1,'PageModuleVisual'),(4,'database_module_title','/img/database.png',3,0,'database',1,1,'DatabaseModuleVisual'),(5,'articles_module_title','/img/articles.png',2,0,'articles',1,1,'ArticleModuleVisual'),(6,'blocks_module_title','/img/blocks.png',2,0,'blocks',1,1,'BlockModuleVisual'),(7,'images_module_title','/img/images.png',2,0,'images',1,1,'ImageModuleVisual'),(8,'templates_module_title','/img/templates.png',4,0,'templates',1,1,'TemplateModuleVisual'),(9,'downloads_module_title','/img/downloads.png',2,0,'downloads',1,1,'DownloadModuleVisual'),(11,'authorization_module_title','/img/authorization.png',3,0,'authorization',1,1,'AuthorizationModuleVisual'),(12,'components_module_title','/img/components.png',3,0,'components',1,1,'ComponentsModuleVisual');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `navigation_title` varchar(255) DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `show_in_navigation` tinyint(1) unsigned NOT NULL,
  `include_in_searchindex` tinyint(1) unsigned NOT NULL,
  `element_holder_id` int(11) unsigned NOT NULL,
  `follow_up` int(10) unsigned NOT NULL,
  `is_homepage` tinyint(1) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  KEY `holder_page` (`element_holder_id`),
  KEY `page_parent` (`parent_id`),
  KEY `element_page` (`element_holder_id`),
  CONSTRAINT `element_page` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Homepage',NULL,1,1,1,0,1,'Homepage');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scopes`
--

DROP TABLE IF EXISTS `scopes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scopes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scopes`
--

LOCK TABLES `scopes` WRITE;
/*!40000 ALTER TABLE `scopes` DISABLE KEYS */;
INSERT INTO `scopes` VALUES (1,'Paragraaf'),(2,'Lijst'),(3,'Afbeelding'),(4,'Download'),(5,'Pagina'),(6,'Blok'),(7,'Artikel overzicht'),(8,'Youtube'),(9,'Article'),(10,'Gastenboek');
/*!40000 ALTER TABLE `scopes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website_title` varchar(255) DEFAULT NULL,
  `frontend_hostname` varchar(255) DEFAULT NULL,
  `backend_hostname` varchar(255) DEFAULT NULL,
  `email_address` varchar(45) DEFAULT NULL,
  `smtp_host` varchar(45) DEFAULT NULL,
  `frontend_template_dir` varchar(255) DEFAULT NULL,
  `static_files_dir` varchar(255) DEFAULT NULL,
  `config_dir` varchar(255) NOT NULL,
  `upload_dir` varchar(255) DEFAULT NULL,
  `database_version` varchar(45) NOT NULL,
  `component_dir` varchar(255) DEFAULT NULL,
  `backend_template_dir` varchar(255) DEFAULT NULL,
  `cms_root_dir` varchar(255) DEFAULT NULL,
  `public_root_dir` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `scope_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (1,'','Nieuw template',1);
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `text_elements_metadata`
--

DROP TABLE IF EXISTS `text_elements_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text_elements_metadata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text,
  `text` text,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `metadata_elements` (`element_id`),
  CONSTRAINT `metadata_elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `text_elements_metadata`
--

LOCK TABLES `text_elements_metadata` WRITE;
/*!40000 ALTER TABLE `text_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `text_elements_metadata` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-31 10:00:27
