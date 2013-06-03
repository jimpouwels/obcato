-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.37-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema site_administration
--

CREATE DATABASE IF NOT EXISTS site_administration;
USE site_administration;

--
-- Definition of table `article_overview_elements_metadata`
--

DROP TABLE IF EXISTS `article_overview_elements_metadata`;
CREATE TABLE `article_overview_elements_metadata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `show_from` datetime default NULL,
  `show_to` datetime default NULL,
  `show_until_today` tinyint(1) NOT NULL default '1',
  `order_by` varchar(45) NOT NULL,
  `title` varchar(255) default NULL,
  `element_id` int(10) unsigned NOT NULL,
  `number_of_results` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `metadata_element` (`element_id`),
  CONSTRAINT `metadata_element` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `article_overview_elements_metadata`
--

/*!40000 ALTER TABLE `article_overview_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `article_overview_elements_metadata` ENABLE KEYS */;


--
-- Definition of table `article_target_pages`
--

DROP TABLE IF EXISTS `article_target_pages`;
CREATE TABLE `article_target_pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `element_holder_id` int(10) unsigned NOT NULL,
  `is_default` tinyint(1) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `article_target_pages` (`element_holder_id`),
  CONSTRAINT `article_target_pages` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `article_target_pages`
--

/*!40000 ALTER TABLE `article_target_pages` DISABLE KEYS */;
INSERT INTO `article_target_pages` (`id`,`element_holder_id`,`is_default`) VALUES 
 (24,1,0);
/*!40000 ALTER TABLE `article_target_pages` ENABLE KEYS */;


--
-- Definition of table `article_terms`
--

DROP TABLE IF EXISTS `article_terms`;
CREATE TABLE `article_terms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `article_terms`
--

/*!40000 ALTER TABLE `article_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `article_terms` ENABLE KEYS */;


--
-- Definition of table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `description` longtext,
  `image_id` int(10) unsigned default NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  `publication_date` datetime NOT NULL,
  `target_page` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `article_element_holder` (`element_holder_id`),
  KEY `articles_pages` (`target_page`),
  CONSTRAINT `articles_pages` FOREIGN KEY (`target_page`) REFERENCES `element_holders` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `article_element_holder` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles`
--

/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;


--
-- Definition of table `articles_element_terms`
--

DROP TABLE IF EXISTS `articles_element_terms`;
CREATE TABLE `articles_element_terms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `element_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `terms` (`term_id`),
  KEY `elements` (`element_id`),
  CONSTRAINT `elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `terms` FOREIGN KEY (`term_id`) REFERENCES `article_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles_element_terms`
--

/*!40000 ALTER TABLE `articles_element_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles_element_terms` ENABLE KEYS */;


--
-- Definition of table `articles_terms`
--

DROP TABLE IF EXISTS `articles_terms`;
CREATE TABLE `articles_terms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `article_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `term_terms` (`term_id`),
  KEY `art_articles` (`article_id`),
  CONSTRAINT `art_articles` FOREIGN KEY (`article_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `term_terms` FOREIGN KEY (`term_id`) REFERENCES `article_terms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles_terms`
--

/*!40000 ALTER TABLE `articles_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `articles_terms` ENABLE KEYS */;


--
-- Definition of table `auth_users`
--

DROP TABLE IF EXISTS `auth_users`;
CREATE TABLE `auth_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(45) default NULL,
  `password` varchar(45) default NULL,
  `email_address` varchar(45) default NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `prefix` varchar(45) default NULL,
  `created_at` datetime NOT NULL,
  `uuid` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_users`
--

/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
INSERT INTO `auth_users` (`id`,`username`,`password`,`email_address`,`first_name`,`last_name`,`prefix`,`created_at`,`uuid`) VALUES 
 (2,'Developer','5c08dfd8c8ade29ff8485a8cc0409cf6','jim.pouwels@gmail.com','Jim','Pouwels','','2011-10-30 12:42:05','4ebbf2bdbc13e');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;


--
-- Definition of table `block_positions`
--

DROP TABLE IF EXISTS `block_positions`;
CREATE TABLE `block_positions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `explanation` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `block_positions`
--

/*!40000 ALTER TABLE `block_positions` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_positions` ENABLE KEYS */;


--
-- Definition of table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `position_id` int(10) unsigned default NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `holder_block` (`element_holder_id`),
  KEY `position_block` (`position_id`),
  CONSTRAINT `holder_block` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `position_block` FOREIGN KEY (`position_id`) REFERENCES `block_positions` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blocks`
--

/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;


--
-- Definition of table `blocks_pages`
--

DROP TABLE IF EXISTS `blocks_pages`;
CREATE TABLE `blocks_pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `page_id` int(10) unsigned NOT NULL,
  `block_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  USING BTREE (`id`),
  KEY `blocks` (`block_id`),
  KEY `pages` (`page_id`),
  CONSTRAINT `blocks` FOREIGN KEY (`block_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pages` FOREIGN KEY (`page_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blocks_pages`
--

/*!40000 ALTER TABLE `blocks_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks_pages` ENABLE KEYS */;


--
-- Definition of table `element_holders`
--

DROP TABLE IF EXISTS `element_holders`;
CREATE TABLE `element_holders` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `template_id` int(10) unsigned default NULL,
  `title` longtext,
  `published` tinyint(1) NOT NULL,
  `scope_id` int(10) unsigned default NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_by` int(10) unsigned default NULL,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `element_holder_user` (`created_by`),
  CONSTRAINT `element_holder_user` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `element_holders`
--

/*!40000 ALTER TABLE `element_holders` DISABLE KEYS */;
INSERT INTO `element_holders` (`id`,`template_id`,`title`,`published`,`scope_id`,`created_at`,`created_by`,`type`) VALUES 
 (1,15,'Home',1,5,'2011-12-25 12:06:52',NULL,'ELEMENT_HOLDER_PAGE');
/*!40000 ALTER TABLE `element_holders` ENABLE KEYS */;


--
-- Definition of table `element_types`
--

DROP TABLE IF EXISTS `element_types`;
CREATE TABLE `element_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `classname` varchar(45) NOT NULL,
  `edit_presentation` varchar(255) NOT NULL,
  `icon_url` varchar(255) default NULL,
  `name` varchar(45) NOT NULL,
  `domain_object` varchar(255) NOT NULL,
  `scope_id` int(10) unsigned default NULL,
  `location` varchar(255) NOT NULL,
  `system_default` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `element_types`
--

/*!40000 ALTER TABLE `element_types` DISABLE KEYS */;
INSERT INTO `element_types` (`id`,`classname`,`edit_presentation`,`icon_url`,`name`,`domain_object`,`scope_id`,`location`,`system_default`) VALUES 
 (1,'TextElement','edit_text_element.php','/admin/static.php?static=/default/img/element_icons/text_element.png','Paragraaf','text_element.php',1,'elements/text_element',1),
 (2,'ListElement','edit_list_element.php','/admin/static.php?static=/default/img/element_icons/list_element.png','Lijst','list_element.php',2,'elements/list_element',1),
 (3,'ImageElement','edit_image_element.php','/admin/static.php?static=/default/img/element_icons/image_element.png','Afbeelding','image_element.php',3,'elements/image_element',1),
 (4,'DownloadElement','edit_download_element.php','/admin/static.php?static=/default/img/element_icons/download_element.png','Download','download_element.php',4,'elements/download_element',1),
 (5,'ArticleOverviewElement','edit_article_overview_element.php','/admin/static.php?static=/default/img/element_icons/article_overview_element.png','Artikel overzicht','article_overview_element.php',7,'elements/article_overview_element',1);
/*!40000 ALTER TABLE `element_types` ENABLE KEYS */;


--
-- Definition of table `elements`
--

DROP TABLE IF EXISTS `elements`;
CREATE TABLE `elements` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `follow_up` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `element_holder_id` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `element_holder` (`element_holder_id`),
  CONSTRAINT `element_holder` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `elements`
--

/*!40000 ALTER TABLE `elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `elements` ENABLE KEYS */;


--
-- Definition of table `image_elements_metadata`
--

DROP TABLE IF EXISTS `image_elements_metadata`;
CREATE TABLE `image_elements_metadata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `image_id` int(10) unsigned default NULL,
  `element_id` int(10) unsigned NOT NULL,
  `alternative_text` varchar(255) default NULL,
  `align` varchar(45) NOT NULL,
  `title` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_images` (`image_id`),
  KEY `fk_elements` (`element_id`),
  CONSTRAINT `fk_elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_images` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image_elements_metadata`
--

/*!40000 ALTER TABLE `image_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `image_elements_metadata` ENABLE KEYS */;


--
-- Definition of table `image_labels`
--

DROP TABLE IF EXISTS `image_labels`;
CREATE TABLE `image_labels` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image_labels`
--

/*!40000 ALTER TABLE `image_labels` DISABLE KEYS */;
/*!40000 ALTER TABLE `image_labels` ENABLE KEYS */;


--
-- Definition of table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime default NULL,
  `created_by` int(10) unsigned default NULL,
  `file_name` longtext,
  `thumb_file_name` longtext,
  PRIMARY KEY  (`id`),
  KEY `images_users` (`created_by`),
  CONSTRAINT `images_users` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images`
--

/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;


--
-- Definition of table `images_labels`
--

DROP TABLE IF EXISTS `images_labels`;
CREATE TABLE `images_labels` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `image_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `images` (`image_id`),
  KEY `labels` (`label_id`),
  CONSTRAINT `images` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `labels` FOREIGN KEY (`label_id`) REFERENCES `image_labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images_labels`
--

/*!40000 ALTER TABLE `images_labels` DISABLE KEYS */;
/*!40000 ALTER TABLE `images_labels` ENABLE KEYS */;


--
-- Definition of table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `target_address` varchar(255) default NULL,
  `type` varchar(45) default NULL,
  `code` varchar(45) NOT NULL,
  `target_element_holder` int(10) unsigned default NULL,
  `parent_element_holder` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `ref` USING BTREE (`target_element_holder`),
  KEY `parent` (`parent_element_holder`),
  CONSTRAINT `parent` FOREIGN KEY (`parent_element_holder`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ref` FOREIGN KEY (`target_element_holder`) REFERENCES `element_holders` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `links`
--

/*!40000 ALTER TABLE `links` DISABLE KEYS */;
INSERT INTO `links` (`id`,`title`,`target_address`,`type`,`code`,`target_element_holder`,`parent_element_holder`) VALUES 
 (2,'Nieuwe link','','INTERNAL','2',NULL,1),
 (3,'Nieuwe link','wefwef','EXTERNAL','3',NULL,1);
/*!40000 ALTER TABLE `links` ENABLE KEYS */;


--
-- Definition of table `list_element_items`
--

DROP TABLE IF EXISTS `list_element_items`;
CREATE TABLE `list_element_items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `text` varchar(255) default NULL,
  `indent` int(10) unsigned NOT NULL,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_list_element_items_1` USING BTREE (`element_id`),
  CONSTRAINT `FK_list_element_items_1` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_element_items`
--

/*!40000 ALTER TABLE `list_element_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_element_items` ENABLE KEYS */;


--
-- Definition of table `list_elements_metadata`
--

DROP TABLE IF EXISTS `list_elements_metadata`;
CREATE TABLE `list_elements_metadata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_list_elements_metadata_1` (`element_id`),
  CONSTRAINT `FK_list_elements_metadata_1` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_elements_metadata`
--

/*!40000 ALTER TABLE `list_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_elements_metadata` ENABLE KEYS */;


--
-- Definition of table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author` varchar(255) NOT NULL,
  `message` longblob NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;


--
-- Definition of table `module_groups`
--

DROP TABLE IF EXISTS `module_groups`;
CREATE TABLE `module_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(45) NOT NULL,
  `follow_up` int(10) unsigned NOT NULL,
  `element_group` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `module_groups`
--

/*!40000 ALTER TABLE `module_groups` DISABLE KEYS */;
INSERT INTO `module_groups` (`id`,`title`,`follow_up`,`element_group`) VALUES 
 (1,'Bestand',1,0),
 (2,'Beheer',3,0),
 (3,'Configuratie',5,0),
 (4,'Vormgeving',4,0),
 (5,'Invoegen',2,1);
/*!40000 ALTER TABLE `module_groups` ENABLE KEYS */;


--
-- Definition of table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(45) NOT NULL,
  `icon_url` varchar(255) default NULL,
  `module_group_id` int(10) unsigned NOT NULL,
  `home_item` tinyint(1) NOT NULL,
  `popup` tinyint(1) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `system_default` tinyint(1) NOT NULL,
  `pre_handler` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`,`title`,`icon_url`,`module_group_id`,`home_item`,`popup`,`identifier`,`enabled`,`system_default`,`pre_handler`) VALUES 
 (1,'Uitloggen','/admin/static.php?static=/logout/img/logout.png',1,0,0,'logout',1,1,'form/handler.php'),
 (2,'Instellingen','/admin/static.php?static=/settings/img/settings.png',3,0,0,'settings',1,1,'forms/settings_form_handler.php'),
 (3,'Pagina\'s','/admin/static.php?static=/pages/img/page.png',2,1,0,'pages',1,1,'form/page_editor_handler.php'),
 (4,'Database','/admin/static.php?static=/database/img/database.png',3,0,0,'database',1,1,NULL),
 (5,'Artikelen','/admin/static.php?static=/articles/img/articles.png',2,1,0,'articles',1,1,'form/article_handler.php'),
 (6,'Blokken','/admin/static.php?static=/blocks/img/blocks.png',2,1,0,'blocks',1,1,'form/block_editor_handler.php'),
 (7,'Modules','/admin/static.php?static=/modules/img/modules.png',3,1,0,'modules',1,1,NULL),
 (8,'Afbeeldingen','/admin/static.php?static=/images/img/images.png',2,1,0,'images',1,1,'form/form_handler.php'),
 (9,'Templates','/admin/static.php?static=/templates/img/templates.png',4,1,0,'templates',1,1,'handler.php');
INSERT INTO `modules` (`id`,`title`,`icon_url`,`module_group_id`,`home_item`,`popup`,`identifier`,`enabled`,`system_default`,`pre_handler`) VALUES 
 (10,'Downloads','/admin/static.php?static=/downloads/img/downloads.png',2,1,0,'downloads',1,1,NULL),
 (11,'Berichten','/admin/static.php?static=/messages/img/messages.png',1,1,0,'messages',1,1,NULL),
 (12,'Authorisatie','/admin/static.php?static=/authorization/img/authorization.png',3,1,0,'authorization',1,1,'handler/user_handler.php');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;


--
-- Definition of table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `navigation_title` varchar(255) default NULL,
  `parent_id` int(10) unsigned default NULL,
  `show_in_navigation` tinyint(1) unsigned NOT NULL,
  `include_in_searchindex` tinyint(3) unsigned NOT NULL,
  `element_holder_id` int(11) unsigned NOT NULL,
  `follow_up` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `holder_page` (`element_holder_id`),
  KEY `page_parent` (`parent_id`),
  KEY `element_page` (`element_holder_id`),
  CONSTRAINT `element_page` FOREIGN KEY (`element_holder_id`) REFERENCES `element_holders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` (`id`,`navigation_title`,`parent_id`,`show_in_navigation`,`include_in_searchindex`,`element_holder_id`,`follow_up`) VALUES 
 (1,'Home',NULL,1,1,1,2);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;


--
-- Definition of table `scopes`
--

DROP TABLE IF EXISTS `scopes`;
CREATE TABLE `scopes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scopes`
--

/*!40000 ALTER TABLE `scopes` DISABLE KEYS */;
INSERT INTO `scopes` (`id`,`name`) VALUES 
 (1,'Paragraaf'),
 (2,'Lijst'),
 (3,'Afbeelding'),
 (4,'Download'),
 (5,'Pagina'),
 (6,'Blok'),
 (7,'Artikel overzicht'),
 (8,'Youtube');
/*!40000 ALTER TABLE `scopes` ENABLE KEYS */;


--
-- Definition of table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `website_title` varchar(255) default NULL,
  `frontend_hostname` varchar(255) default NULL,
  `backend_hostname` varchar(255) default NULL,
  `email_address` varchar(45) default NULL,
  `smtp_host` varchar(45) default NULL,
  `template_dir` varchar(255) default NULL,
  `root_dir` longtext,
  `static_files_dir` varchar(255) default NULL,
  `config_dir` varchar(255) NOT NULL,
  `upload_dir` varchar(255) default NULL,
  `database_version` varchar(45) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`,`website_title`,`frontend_hostname`,`backend_hostname`,`email_address`,`smtp_host`,`template_dir`,`root_dir`,`static_files_dir`,`config_dir`,`upload_dir`,`database_version`) VALUES 
 (1,'Test Website','localhost','localhost','jim.pouwels@gmail.com','smtp.chello.nl','D:/Jim/websites/pouwels/cms/trunk/templates','D:/Jim/websites/pouwels/cms/trunk','D:/Jim/websites/pouwels/cms/trunk/static','D:/Jim/websites/pouwels/cms/trunk/conf','D:/Jim/websites/pouwels/cms/trunk/upload','0.0.2');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;


--
-- Definition of table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `scope_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `templates`
--

/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` (`id`,`filename`,`name`,`scope_id`) VALUES 
 (3,'','Nieuw template',NULL),
 (4,'','Nieuw template',NULL),
 (7,'','Nieuw template',NULL),
 (8,'','Nieuw template',NULL),
 (9,'','Nieuw template',NULL),
 (10,'','Nieuw template',NULL),
 (11,'','Nieuw template',NULL),
 (12,'','Nieuw template',NULL),
 (13,'','Nieuw template',NULL);
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;


--
-- Definition of table `text_elements_metadata`
--

DROP TABLE IF EXISTS `text_elements_metadata`;
CREATE TABLE `text_elements_metadata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` text,
  `text` text,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `metadata_elements` (`element_id`),
  CONSTRAINT `metadata_elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `text_elements_metadata`
--

/*!40000 ALTER TABLE `text_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `text_elements_metadata` ENABLE KEYS */;


--
-- Definition of table `youtube_elements_metadata`
--

DROP TABLE IF EXISTS `youtube_elements_metadata`;
CREATE TABLE `youtube_elements_metadata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `embed` longblob NOT NULL,
  `element_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_youtube_elements_metadata_1` (`element_id`),
  CONSTRAINT `FK_youtube_elements_metadata_1` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `youtube_elements_metadata`
--

/*!40000 ALTER TABLE `youtube_elements_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `youtube_elements_metadata` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
