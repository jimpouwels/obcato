-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version    5.0.37-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Definition of table `article_overview_elements_metadata`
--

DROP TABLE IF EXISTS `article_overview_elements_metadata`;
CREATE TABLE `article_overview_elements_metadata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `show_from` datetime default NULL,
  `show_to` datetime default NULL,
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
INSERT INTO `article_overview_elements_metadata` (`id`,`show_from`,`show_to`,`order_by`,`title`,`element_id`,`number_of_results`) VALUES 
 (1,NULL,NULL,'PublicationDate','',21,NULL),
 (5,NULL,NULL,'SortDate','',25,NULL),
 (6,NULL,NULL,'PublicationDate','',26,NULL);
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
 (27,1,1);
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
INSERT INTO `article_terms` (`id`,`name`) VALUES 
 (3,'Bandleden'),
 (4,'Agenda'),
 (5,'Nieuws');
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
  `sort_date` datetime NOT NULL,
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
INSERT INTO `articles` (`id`,`description`,`image_id`,`element_holder_id`,`publication_date`,`target_page`,`sort_date`) VALUES 
 (3,'Gitaar en zang.\r\nwebsite beheer,social media beheer.\r\n\r\n<strong>In het kort</strong> Enorm fan van \"The Beatles\" en verder zo\\\\\\\'n beetje alles wat oud (en soms ook nieuw) is (Rock \\\\\\\'n Roll/Rock a Billy/Blues/60\\\\\\\'s etc.).',2,21,'2014-09-28 17:13:12',NULL,'2014-09-28 17:13:12'),
 (4,'Basgitaar en zang.',3,47,'2014-10-10 22:58:56',NULL,'2014-10-10 22:58:56'),
 (5,'Gitaar en zang\r\n\r\n<strong>In het kort:</strong>\r\nHoudt van een pilske (meestal 1 teveel). Houdt van alle muzieksoorten maar voornamelijk het ruigere rockwerk.',5,48,'2014-10-10 22:59:25',NULL,'2014-10-10 22:59:25'),
 (6,'Drums en zang.\r\n\r\n<strong>In het kort:</strong>\r\nHoudt van alle muziek maar voornamelijk het ouder spul (aangezien hij zelf ook al bij het ouder spul hoort).',4,49,'2014-10-10 23:00:15',NULL,'2014-10-10 23:00:15'),
 (7,'Op Vrijdag 17 Oktober staan wij naar aanleiding van een benefietavond voor de nierstichting samen met de bands \'Life\' en \'The Fashions\' op de planken bij Cafe Zeezicht aan de Steenweg 2-4 in Helmond.\r\n\r\nAanvang: nog niet bekend\r\nEntree: nog niet bekend ',NULL,51,'2014-10-10 00:00:00',NULL,'2014-10-17 00:00:00'),
 (8,'Op Zaterdag 11 Oktober staan wij wederom op de planken bij Buurthuis St. Anna aan de Hoogeindsestraat 24 in Helmond.\r\n\r\nAanvang: 20:30\r\nEntree: nog niet bekend',NULL,52,'2014-10-10 00:00:00',NULL,'2014-10-11 00:00:00');
INSERT INTO `articles` (`id`,`description`,`image_id`,`element_holder_id`,`publication_date`,`target_page`,`sort_date`) VALUES 
 (9,'Op vrijdagavond 22 Augustus staan wij weer op de planken bij Cafe In den Sleutel (bij velen gewoon bekend als cafÃ© Van Hoof) in Deurne.\r\n\r\nEntree: Gratis\r\nAanvang: 20:30u\r\n\r\nWordt wederom een gezellige avond dus wees er op tijd bij! \r\n\r\nHopelijk tot dan!',NULL,53,'2014-08-20 00:00:00',NULL,'2014-08-22 00:00:00'),
 (10,'Op Donderdag 29 Mei staan wij tijdens Hemelvaart bij Cafe Franske aan het Havenplein in Helmond!\r\nAanvang: 16:30 \r\nEntree: Gratis!',NULL,54,'2014-10-11 00:00:00',NULL,'2014-05-29 00:00:00'),
 (11,'Zaterdag Avond 14 Juni staan wij tijden Tungelroy Kermis in CafÃ¨-Zaal Kimpe Veld aan het St. Barbaraplein 2 in Tungelroy.\r\nAanvang: ongeveer 20:30 \r\nEntree: Gratis',NULL,55,'2014-10-11 00:00:00',NULL,'2014-06-14 00:00:00'),
 (12,'Op Dinsdag 24 Juni Staan wij tijdens Deurne Kermis in Cafe In Den Sleutel aan de Stationsstraat 8 in Deurne.\r\nAanvang omstreeks 20.30u',NULL,56,'2014-10-11 00:00:00',NULL,'2014-06-24 00:00:00'),
 (13,'Op Zondag 25 Mei geven wij een benefiet optreden voor de Stichting Kindervakantieweek Helmond-Oost. Het begint rond de klok van 3 uur en de 3 euro entree komt ten goede aan de kinder vakantieweek dus komt allen dan heb je naast een leuke middag ook nog eens een goed doel gesteund :)',NULL,57,'2014-10-11 00:00:00',NULL,'2014-05-25 00:00:00');
INSERT INTO `articles` (`id`,`description`,`image_id`,`element_holder_id`,`publication_date`,`target_page`,`sort_date`) VALUES 
 (14,'Op Zaterdag 21 December spelen we bij Cafe Franske aan de Havenweg 4 in Helmond. \r\nAanvang 20:30u',NULL,58,'2014-10-11 00:00:00',NULL,'2013-12-21 00:00:00'),
 (15,'Op Vrijdag 13 December spelen wij bij Cafe Bar De Bascule op de Havenweg in Helmond! \r\nAanvang 21:00',NULL,59,'2014-10-11 00:00:00',NULL,'2013-12-13 00:00:00'),
 (16,'Op Zaterdagavond 2 November spelen wij weer bij Cafe Biljart Den Tram aan de Marktstraat 20 in Helmond.\r\nAanvang 20:30 en gratis entree.\r\n',NULL,60,'2014-10-11 00:00:00',NULL,'2013-11-02 00:00:00'),
 (17,'Op zaterdagavond 23 November spelen wij in de kantine van SC Oranje Zwart aan sportpark De Braak.\r\nAanvang 20:30, gratis entree en open voor iedereen!',NULL,61,'2014-10-11 00:00:00',NULL,'2013-11-23 00:00:00'),
 (18,'Op Zaterdagavond 19 Oktober spelen wij op een helaas besloten feest en zijn wij niet meer te boeken.',NULL,62,'2014-10-11 00:00:00',NULL,'2013-10-19 00:00:00'),
 (19,'Op Zaterdagavond 12 Oktober staan wij in cafe Plein 5 aan het  Wilhelmina Plein 5 in Someren.\r\nAanvang rond de klok van half 9 en de toegang is gratis!',NULL,63,'2014-10-11 00:00:00',NULL,'2013-10-12 00:00:00');
INSERT INTO `articles` (`id`,`description`,`image_id`,`element_holder_id`,`publication_date`,`target_page`,`sort_date`) VALUES 
 (20,'Na lang wachten (no offense Jim) is ie dan vanaf nu online en (als het goed is ook) operationeel. Vanaf nu kun je dus deze website in de gaten houden voor info over de band,optredens,media en nog veel meer! We gaan meteen aan de slag dus check regelmatig om op de hoogte te blijven!',NULL,64,'2011-12-06 00:00:00',NULL,'2014-10-11 00:00:00'),
 (21,'Op Zaterdag 14 Januari treden wij op in Buurthuis St. Anna op de Hoogeindsestraat in Helmond (op d\'n olliemeule!). Maar omdat we dan toevallig ook 1 jaar bestaan willen we het iets groter aanpakken dan anders. We hopen daarom dat jij ook komt om er een super gezellige avond van te maken! De entree is â‚¬2,50 maarrrr..... het bier is een stuk goedkoper dus die entree heb je zo terug ;) We zien je (hopelijk) op de 14e!',NULL,65,'2011-12-06 00:00:00',NULL,'2014-10-11 00:00:00'),
 (22,'Wood 88 Wenst iedereen een gezond en voorspoedig 2012!',NULL,66,'2012-01-01 00:00:00',NULL,'2014-10-11 00:00:00'),
 (23,'Gister avond was het dan zover,ons optreden bij buurthuis St. Anna en daarmee de viering van ons eenjarig bestaan...',NULL,67,'2012-01-15 00:00:00',NULL,'2014-10-11 00:00:00');
INSERT INTO `articles` (`id`,`description`,`image_id`,`element_holder_id`,`publication_date`,`target_page`,`sort_date`) VALUES 
 (24,'Voornamelijk omdat ik (Sander) erg lui ben geweest is er de afgelopen tijd (lees: heul veul tijd) weinig nieuws op de site te lezen geweest.\r\nMaar dat wil natuurlijk niet zeggen dat er niks gebeurd is in en om de band de afgelopen tijd.\r\n',NULL,68,'2012-09-28 00:00:00',NULL,'2014-10-11 00:00:00'),
 (25,'Sinds gister avond omstreeks 11 uur zijn Gerrit en Sanne van Lierop de ouders geworden van de mooie Lily!\r\nmoeder en dochter maken het goed, vader heeft het erg druk ;-). ',NULL,69,'2012-09-28 00:00:00',NULL,'2014-10-11 00:00:00'),
 (26,'Ja mensen, ondanks dat het niet mee valt nu Cindy weg is gaan de repetities weer steeds beter. We zijn druk bezig met nieuwe nummers en hopen binnenkort toch weer ergens een of ander podium onveilig te maken. Tot snel!',NULL,70,'2012-10-20 00:00:00',NULL,'2014-10-11 00:00:00'),
 (27,'Kreeg ik gister avond toch ineens te horen dat ik ons optreden bij St. Anna niet op de website geplaatst had. Mooie webmaster ben ik. Dus daarvoor mijn excuses.\r\nMaar ondanks dat wist iedereen het mede dankzij Facebook gelukkig goed te vinden want het was weer volle bak.\r\nWe hebben echt een gezellige avond gehad en ik wil iedereen die erbij was heel erg bedanken voor jullie enthousiasme.\r\nDat werkte echt goed op ons door en het dak ging er weer af.\r\nNu genieten we even na en gaan meteen aan de slag voor nieuwe optredens en nieuwe nummers voor jullie.\r\nNamens de band nogmaals iedereen bedankt en hopelijk tot de volgende keer!\r\n\r\nSander van de Kerkhof',NULL,71,'2013-04-21 00:00:00',NULL,'2014-10-11 00:00:00'),
 (28,'Na een lange tijd hard werken begint het eindelijk een beetje te lopen wat betreft optredens! klik snel op het kopje agenda in het menu links aan de pagina en kijk waar we binnekort zoal staan. ',NULL,72,'2013-10-11 00:00:00',NULL,'2014-10-11 00:00:00');
INSERT INTO `articles` (`id`,`description`,`image_id`,`element_holder_id`,`publication_date`,`target_page`,`sort_date`) VALUES 
 (29,'Let op!! er zijn een aantal veranderingen in de data van onze optredens. Klik op het kopje agenda links in het menu om de nieuwe data en tijden te zien. Onze excuses voor het ongemak.',NULL,73,'2013-10-11 00:00:00',NULL,'2014-10-11 00:00:00'),
 (30,'Ondanks dat de site al een tijdje niet meer geupdate is zijn wij de laatste maanden toch druk bezig geweest met optredens, het verbouwen van ons repetitie hok en het regelen van nieuwe optredens. Met name dat laatste is weer goed gelukt en onder het kopje agenda vind je meer info. kijk regelmatig want we zijn nog met allerhande partijen in gesprek. :)',NULL,74,'2014-03-20 00:00:00',NULL,'2014-10-11 00:00:00'),
 (31,'De Repetities gaan lekker, er komen steeds meer nummers bij en we maken steeds meer nummers eigen, de optredens druppelen langzaam maar zeker binnen en de band zit lekker in zijn vel.\r\nKortom: we gaan lekker!',NULL,75,'2014-05-14 00:00:00',NULL,'2014-10-11 00:00:00');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;


--
-- Definition of table `articles_element_terms`
--

DROP TABLE IF EXISTS `articles_element_terms`;
CREATE TABLE `articles_element_terms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `element_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  USING BTREE (`id`),
  KEY `terms` (`term_id`),
  KEY `elements` (`element_id`),
  CONSTRAINT `elements` FOREIGN KEY (`element_id`) REFERENCES `elements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `terms` FOREIGN KEY (`term_id`) REFERENCES `article_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `articles_element_terms`
--

/*!40000 ALTER TABLE `articles_element_terms` DISABLE KEYS */;
INSERT INTO `articles_element_terms` (`id`,`element_id`,`term_id`) VALUES 
 (1,21,3),
 (2,25,4),
 (3,26,5);
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
INSERT INTO `articles_terms` (`id`,`article_id`,`term_id`) VALUES 
 (1,49,3),
 (2,47,3),
 (3,48,3),
 (4,21,3),
 (5,51,4),
 (6,52,4),
 (7,53,4),
 (8,54,4),
 (9,55,4),
 (10,56,4),
 (11,57,4),
 (12,58,4),
 (13,59,4),
 (14,60,4),
 (15,61,4),
 (16,62,4),
 (17,63,4),
 (18,64,5),
 (19,65,5),
 (20,66,5),
 (21,67,5),
 (22,68,5),
 (23,69,5),
 (24,70,5),
 (25,71,5),
 (26,72,5),
 (27,73,5),
 (28,74,5),
 (29,75,5);
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
 (2,'Developer','5c08dfd8c8ade29ff8485a8cc0409cf6','jim.pouwels@gmail.com','Jim','Pouwels','','2011-10-30 12:42:05','4ebbf2bdbc13e'),
 (3,'sandervdk','c638adce80ea884bd78bb5e373389f30','sandervandekerkhof@gmail.com','Sander','Kerkhof','van de','2011-12-06 18:36:17','4ede60a19fd86');
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
-- Definition of table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE `downloads` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `file_name` varchar(255) default NULL,
  `published` tinyint(1) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(10) unsigned default NULL,
  `title` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `DOWNLOADS_2_USERS` (`created_by`),
  CONSTRAINT `DOWNLOADS_2_USERS` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `downloads`
--

/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;


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
 (1,1,'Homepage',1,5,'2014-09-07 10:37:10',NULL,'ELEMENT_HOLDER_PAGE'),
 (21,NULL,'Sander van de Kerkhof',1,9,'2014-10-10 22:57:11',2,'ELEMENT_HOLDER_ARTICLE'),
 (34,1,'SubSub',1,5,'2014-09-28 18:18:25',2,'ELEMENT_HOLDER_PAGE'),
 (35,1,'Nieuws',1,5,'2014-10-10 21:55:27',2,'ELEMENT_HOLDER_PAGE'),
 (36,1,'Foto\'s',1,5,'2014-10-10 22:25:11',2,'ELEMENT_HOLDER_PAGE'),
 (39,NULL,'Nieuwe pagina',0,5,'2014-10-10 22:27:35',2,'ELEMENT_HOLDER_PAGE'),
 (40,1,'De band',1,5,'2014-10-10 22:35:58',2,'ELEMENT_HOLDER_PAGE'),
 (44,1,'Playlist',1,5,'2014-10-10 22:36:37',2,'ELEMENT_HOLDER_PAGE'),
 (45,1,'Media',1,5,'2014-10-10 22:43:04',2,'ELEMENT_HOLDER_PAGE'),
 (46,1,'Contact',1,5,'2014-10-10 22:44:34',2,'ELEMENT_HOLDER_PAGE'),
 (47,NULL,'Mark Heldens',1,9,'2014-10-10 22:59:22',2,'ELEMENT_HOLDER_ARTICLE'),
 (48,NULL,'Ron van der Bruggen',1,9,'2014-10-10 22:59:56',2,'ELEMENT_HOLDER_ARTICLE'),
 (49,NULL,'Gerrit van Lierop',1,9,'2014-10-10 23:00:27',2,'ELEMENT_HOLDER_ARTICLE');
INSERT INTO `element_holders` (`id`,`template_id`,`title`,`published`,`scope_id`,`created_at`,`created_by`,`type`) VALUES 
 (50,1,'Agenda',1,5,'2014-10-10 23:15:57',2,'ELEMENT_HOLDER_PAGE'),
 (51,NULL,'Vrijdag 17 Oktober Cafe Zeezicht',1,9,'2014-10-10 23:21:31',2,'ELEMENT_HOLDER_ARTICLE'),
 (52,NULL,'Zaterdag 11 Oktober St. Anna ',1,9,'2014-10-10 23:26:20',2,'ELEMENT_HOLDER_ARTICLE'),
 (53,NULL,'Vrijdag 22 Aug. Cafe In Den Sleutel Deurne',1,9,'2014-10-10 23:53:30',2,'ELEMENT_HOLDER_ARTICLE'),
 (54,NULL,'Cafe Franske @ Hemelvaart!',1,9,'2014-10-11 07:14:04',2,'ELEMENT_HOLDER_ARTICLE'),
 (55,NULL,'Tungelroy Kermis',1,9,'2014-10-11 07:14:49',2,'ELEMENT_HOLDER_ARTICLE'),
 (56,NULL,'Deurne Kermis - Cafe In Den Sleutel',1,9,'2014-10-11 07:15:10',2,'ELEMENT_HOLDER_ARTICLE'),
 (57,NULL,'Benefiet optreden wijkhuis De Lier',1,9,'2014-10-11 07:15:36',2,'ELEMENT_HOLDER_ARTICLE'),
 (58,NULL,'Cafe Franske',1,9,'2014-10-11 07:15:59',2,'ELEMENT_HOLDER_ARTICLE'),
 (59,NULL,'Bascule 2013',1,9,'2014-10-11 07:16:19',2,'ELEMENT_HOLDER_ARTICLE'),
 (60,NULL,'Cafe Biljart D\'n Tram',1,9,'2014-10-11 07:16:39',2,'ELEMENT_HOLDER_ARTICLE');
INSERT INTO `element_holders` (`id`,`template_id`,`title`,`published`,`scope_id`,`created_at`,`created_by`,`type`) VALUES 
 (61,NULL,'SC Oranje Zwart',1,9,'2014-10-11 07:16:59',2,'ELEMENT_HOLDER_ARTICLE'),
 (62,NULL,'Besloten Optreden',1,9,'2014-10-11 07:17:23',2,'ELEMENT_HOLDER_ARTICLE'),
 (63,NULL,'Cafe Plein 5 Someren',1,9,'2014-10-11 07:18:05',2,'ELEMENT_HOLDER_ARTICLE'),
 (64,NULL,'Vernieuwde website!',1,9,'2014-10-11 07:19:18',2,'ELEMENT_HOLDER_ARTICLE'),
 (65,NULL,'Wood 88 bestaat 1 jaar!!',1,9,'2014-10-11 07:22:21',2,'ELEMENT_HOLDER_ARTICLE'),
 (66,NULL,'Gelukkig nieuwjaar!',1,9,'2014-10-11 07:23:31',2,'ELEMENT_HOLDER_ARTICLE'),
 (67,NULL,'Buurthuis St. Anna',1,9,'2014-10-11 07:24:16',2,'ELEMENT_HOLDER_ARTICLE'),
 (68,NULL,'Heel veel nieuws!',1,9,'2014-10-11 07:24:38',2,'ELEMENT_HOLDER_ARTICLE'),
 (69,NULL,'Gerrit van Lierop vader geworden!',1,9,'2014-10-11 07:25:02',2,'ELEMENT_HOLDER_ARTICLE'),
 (70,NULL,'Opkrabbelen',1,9,'2014-10-11 07:25:26',2,'ELEMENT_HOLDER_ARTICLE'),
 (71,NULL,'Buurthuis St. Anna 2013',1,9,'2014-10-11 07:27:51',2,'ELEMENT_HOLDER_ARTICLE'),
 (72,NULL,'Nieuwe optredens!',1,9,'2014-10-11 07:28:11',2,'ELEMENT_HOLDER_ARTICLE');
INSERT INTO `element_holders` (`id`,`template_id`,`title`,`published`,`scope_id`,`created_at`,`created_by`,`type`) VALUES 
 (73,NULL,'BELANGRIJK: Data gewijzigd!',1,9,'2014-10-11 07:28:29',2,'ELEMENT_HOLDER_ARTICLE'),
 (74,NULL,'Nieuwe optredens!',1,9,'2014-10-11 07:28:50',2,'ELEMENT_HOLDER_ARTICLE'),
 (75,NULL,'We gaan lekker!',1,9,'2014-10-11 07:29:10',2,'ELEMENT_HOLDER_ARTICLE');
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
  `identifier` varchar(255) default NULL,
  `system_default` tinyint(1) NOT NULL,
  `destroy_script` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `element_types`
--

/*!40000 ALTER TABLE `element_types` DISABLE KEYS */;
INSERT INTO `element_types` (`id`,`classname`,`edit_presentation`,`icon_url`,`name`,`domain_object`,`scope_id`,`identifier`,`system_default`,`destroy_script`) VALUES 
 (1,'TextElement','edit_text_element.php','text_element.png','Paragraaf','text_element.php',1,'text_element',1,NULL),
 (2,'ListElement','edit_list_element.php','list_element.png','Lijst','list_element.php',2,'list_element',1,NULL),
 (3,'ImageElement','edit_image_element.php','image_element.png','Afbeelding','image_element.php',3,'image_element',1,NULL),
 (4,'DownloadElement','edit_download_element.php','download_element.png','Download','download_element.php',4,'download_element',1,NULL),
 (5,'ArticleOverviewElement','edit_article_overview_element.php','article_overview_element.png','Artikel overzicht','article_overview_element.php',7,'article_overview_element',1,NULL);
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
INSERT INTO `elements` (`id`,`follow_up`,`type_id`,`element_holder_id`,`template_id`) VALUES 
 (11,0,1,36,4),
 (12,1,1,36,4),
 (13,0,1,40,4),
 (14,0,1,44,4),
 (15,1,1,44,4),
 (16,0,1,45,4),
 (17,1,1,45,4),
 (18,0,1,46,4),
 (19,1,1,46,4),
 (20,2,1,46,4),
 (21,1,5,40,7),
 (25,0,5,50,8),
 (26,0,5,35,9),
 (27,0,1,67,4),
 (28,1,1,67,0),
 (29,2,1,67,0);
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
INSERT INTO `images` (`id`,`title`,`published`,`created_at`,`created_by`,`file_name`,`thumb_file_name`) VALUES 
 (2,'Sander',1,'2014-10-10 22:54:42',2,'UPLIMG-002_sander.jpg','THUMB-UPLIMG-002_sander.jpg'),
 (3,'Mark',1,'2014-10-10 22:55:20',2,'UPLIMG-003_mark.jpg','THUMB-UPLIMG-003_mark.jpg'),
 (4,'Gerrit',1,'2014-10-10 22:55:31',2,'UPLIMG-004_gerrit.jpg','THUMB-UPLIMG-004_gerrit.jpg'),
 (5,'Ron',1,'2014-10-10 22:55:42',2,'UPLIMG-005_ron.jpg','THUMB-UPLIMG-005_ron.jpg');
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
 (1,'Mail naar Wood 88','mailto:info@wood88.nl','EXTERNAL','1',NULL,44),
 (2,'Mail naar Wood88','mailto:info@wood88.nl','EXTERNAL','1',NULL,46),
 (3,'Facebook','http://www.facebook.com/band.wood88','EXTERNAL','2',NULL,46),
 (4,'Twitter','http://twitter.com/wood88','EXTERNAL','3',NULL,46);
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
  `popup` tinyint(1) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `system_default` tinyint(1) NOT NULL,
  `class` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` (`id`,`title`,`icon_url`,`module_group_id`,`popup`,`identifier`,`enabled`,`system_default`,`class`) VALUES 
 (1,'Uitloggen','/logout/img/logout.png',1,0,'logout',1,1,'LogoutModuleVisual'),
 (2,'Instellingen','/settings/img/settings.png',3,0,'settings',1,1,'SettingsModuleVisual'),
 (3,'Pagina\'s','/pages/img/page.png',2,0,'pages',1,1,'PageModuleVisual'),
 (4,'Database','/database/img/database.png',3,0,'database',1,1,'DatabaseModuleVisual'),
 (5,'Artikelen','/articles/img/articles.png',2,0,'articles',1,1,'ArticleModuleVisual'),
 (6,'Blokken','/blocks/img/blocks.png',2,0,'blocks',1,1,'BlockModuleVisual'),
 (7,'Afbeeldingen','/images/img/images.png',2,0,'images',1,1,'ImageModuleVisual'),
 (8,'Templates','/templates/img/templates.png',4,0,'templates',1,1,'TemplateModuleVisual'),
 (9,'Downloads','/downloads/img/downloads.png',2,0,'downloads',1,1,'DownloadModuleVisual'),
 (10,'Berichten','/messages/img/messages.png',1,0,'messages',1,1,NULL),
 (11,'Authorisatie','/authorization/img/authorization.png',3,0,'authorization',1,1,'AuthorizationModuleVisual');
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
  `is_homepage` tinyint(1) default NULL,
  `description` longtext,
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
INSERT INTO `pages` (`id`,`navigation_title`,`parent_id`,`show_in_navigation`,`include_in_searchindex`,`element_holder_id`,`follow_up`,`is_homepage`,`description`) VALUES 
 (1,'Navigatietitel van de homepage',NULL,1,1,1,3,1,'Dit is de beschrijving van de pagina: Ga naar [LINK C=\"1\"]google[/LINK]'),
 (16,'SubSub',33,1,1,34,0,0,''),
 (17,'Nieuws',1,1,1,35,0,0,''),
 (18,'Foto\'s',1,1,1,36,4,0,''),
 (22,'De band',1,1,1,40,1,0,''),
 (26,'Playlist',1,1,1,44,3,0,''),
 (27,'Media',1,1,1,45,5,0,''),
 (28,'Contact',1,1,1,46,6,0,''),
 (29,'Agenda',1,1,1,50,2,0,'');
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
 (8,'Youtube'),
 (9,'Article'),
 (10,'Gastenboek');
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
  `frontend_template_dir` varchar(255) default NULL,
  `static_files_dir` varchar(255) default NULL,
  `config_dir` varchar(255) NOT NULL,
  `upload_dir` varchar(255) default NULL,
  `database_version` varchar(45) NOT NULL,
  `component_dir` varchar(255) default NULL,
  `backend_template_dir` varchar(255) default NULL,
  `root_dir` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`,`website_title`,`frontend_hostname`,`backend_hostname`,`email_address`,`smtp_host`,`frontend_template_dir`,`static_files_dir`,`config_dir`,`upload_dir`,`database_version`,`component_dir`,`backend_template_dir`,`root_dir`) VALUES 
 (1,'Wood 88','www.wood88.nl','www.wood88.nl','','','','','','','1.0.0','','','');
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
 (1,'page.tpl','Pagina template',5),
 (4,'paragraph.tpl','Paragraaf template',1),
 (5,'list_default.tpl','Nieuw template',2),
 (6,'image_default.tpl','Nieuw template',3),
 (7,'article_overview_band.tpl','De Band',7),
 (8,'article_overview_agenda.tpl','Agenda',7),
 (9,'article_overview_nieuws.tpl','Nieuws',7);
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
INSERT INTO `text_elements_metadata` (`id`,`title`,`text`,`element_id`) VALUES 
 (4,'Bandpics','<embed type=\"application/x-shockwave-flash\" src=\"https://static.googleusercontent.com/external_content/picasaweb.googleusercontent.com/slideshow.swf\" width=\"600\" height=\"400\" flashvars=\"host=picasaweb.google.com&hl=nl&feat=flashalbum&RGB=0x000000&feed=https%3A%2F%2Fpicasaweb.google.com%2Fdata%2Ffeed%2Fapi%2Fuser%2F107050669382382702973%2Falbumid%2F5954624562704346497%3Falt%3Drss%26kind%3Dphoto%26hl%3Dnl\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>',11),
 (5,'Bascule 2013','<embed type=\"application/x-shockwave-flash\" src=\"https://static.googleusercontent.com/external_content/picasaweb.googleusercontent.com/slideshow.swf\" width=\"600\" height=\"400\" flashvars=\"host=picasaweb.google.com&hl=nl&feat=flashalbum&RGB=0x000000&feed=https%3A%2F%2Fpicasaweb.google.com%2Fdata%2Ffeed%2Fapi%2Fuser%2F107050669382382702973%2Falbumid%2F5957612911517344049%3Falt%3Drss%26kind%3Dphoto%26hl%3Dnl\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>',12),
 (6,'','Wij zijn een pop/rock/rock \'n roll georienteerde band uit Helmond met tot nog toe alleen maar covers. Aan eigen werk wordt nog hard gewerkt. Zoek je een band voor een avond of voor een uurtje? Alles kan en wij nemen onze eigen geluidsinstallatie en geluidsman mee dus die hoef je ook niet meer in te huren!\r\n\r\nHieronder worden de bendeleden voorgesteld: ',13);
INSERT INTO `text_elements_metadata` (`id`,`title`,`text`,`element_id`) VALUES 
 (7,'Hieronder een greep uit ons repertoire.','Ain\'t No Sunshine \r\nAnother 45 Miles\r\nApache\r\nBad Case Of Loving You\r\nBad Moon Rising\r\nBlauw\r\nBorn To Be Wild\r\nBrown Eyed Girl\r\nBudapest\r\nCan\'t Get Enough Of Your Love\r\nCocaine\r\nCocaine Blues\r\nDrivin\' My Life Away\r\nF.B.I.\r\nFolsom Prison Blues\r\nGimme All Your Lovin\'\r\nHave You Ever Seen The Rain\r\nHound Dog\r\nIedereen Is Van De Wereld\r\nIf God Was A Woman\r\nI Will Folow\r\nJimmy\r\nLa Bamba\r\nLearn To Fly\r\nLife In The Fast Lane\r\nMake Me Smile\r\nMove It\r\nMustang Sally\r\nNeed Your Love So Bad\r\nNever Be Clever\r\nOnly Wanna Be With You\r\nPeter Gunn Theme\r\nRave On\r\nRockin\' in a Free World\r\nSchoolplein\r\nSex On Fire\r\nSharp Dressed Man\r\nShooting Star\r\nThe One I Love\r\nWhat I Like About You\r\nWhen You Walk In The Room\r\nWherever You Will Go\r\nWhiskey In The Jar\r\nWhite Room\r\nWhole Lot Of Leavin\'',14),
 (8,'Mail je suggestie!','vind jij dat hier jou nummer bij hoort te staan?\r\n[LINK C=\"1\"]Mail[/LINK] ons je suggestie en we kijken wat we voor je kunnen doen. ',15);
INSERT INTO `text_elements_metadata` (`id`,`title`,`text`,`element_id`) VALUES 
 (9,'','Hieronder kun je video- en geluidsfragmenten terugvinden van verschillende optredens van Wood 88 in het verleden.\r\n\r\n',16),
 (10,'F.B.I. Bij Cafe plein 5 Someren','<iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/22C8LRo5GRA\" frameborder=\"0\" allowfullscreen></iframe>',17),
 (11,'Voor meer informatie, vragen over deze site of boekingen:','E-mail: [LINK C=\"1\"]info@wood88.nl[/LINK]\r\n\r\n',18),
 (12,'Voor boekingen telefonisch:','Ron van den Bruggen:   06 41 29 51 76\r\nSander van de Kerkhof: 06 53 28 68 96\r\n\r\n\r\n',19),
 (13,'Social media:','[LINK C=\"2\"]Facebook[/LINK]\r\n[LINK C=\"3\"]Twitter[/LINK]',20),
 (14,'Het optreden','Rond de klok van 9 uur begon het vol te lopen en om kwart over 9 tikten we af. De Sfeer zat er vanaf het begin al meteen goed in en naarmate de avond vorderde kwam iedereen inclusief de band steeds meer los.\r\nMet Self esteem als afsluiter vloog het bier in de rondte en werd er van alle kanten gesprongen en mee gezongen/geschreeuwd. Kortom een zeer geslaagde avond.',27),
 (15,'Afscheid Dennis Dankers','Vlak voor het einde van de avond hadden we het enige minpuntje namelijk het afscheid van onze altijd rockende bassist Dennis Dankers. Na het krijgen van een bos bloemen (die hij meteen de zaal in mieterde zoals het een echte rocker betaamt) kwam hij nog meer los en tijdens de laatste nummers ging het dak eraf.  We kunnen alleen maar hopen dat de volgende bassist minstens een beetje van Dennis\' enthousiasme in zich heeft maar dat zal niet meevallen.',28);
INSERT INTO `text_elements_metadata` (`id`,`title`,`text`,`element_id`) VALUES 
 (16,'Bedankt!','Alle aanwezigen hardstikke bedankt voor jullie enthousiasme en de gezelligheid en we hopen snel weer terug te zijn met nieuwe optredens.',29);
/*!40000 ALTER TABLE `text_elements_metadata` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
