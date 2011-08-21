# ************************************************************
# Sequel Pro SQL dump
# Version 3389
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.9)
# Database: grandlarge
# Generation Time: 2011-08-20 19:49:54 -0400
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table about
# ------------------------------------------------------------

DROP TABLE IF EXISTS `about`;

CREATE TABLE `about` (
  `id` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `image` varchar(255) NOT NULL DEFAULT '',
  `col1` text NOT NULL,
  `col2` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `about` WRITE;
/*!40000 ALTER TABLE `about` DISABLE KEYS */;

INSERT INTO `about` (`id`, `image`, `col1`, `col2`)
VALUES
	(1,'gl_about.jpg','Grand Large is an international production company founded by Steve Horton in Paris, France June 2001.\n\nTogether with a diverse group of directors and designers, Grand Large is known for its ability to deliver effective commercials, new media content and music videos.','Clients include Jaguar, Ray Ban, Hilfiger, Cartier, L\'oreal, Coca Cola, Avon, Marionnaud, Mars, COI, Schwinn, Verizon, Nivea, Moby, Sundance Channel, The New York Times&hellip;\n\nOffices in New York and Paris give us the flexibility to produce and foster creative talent on the international stage.');

/*!40000 ALTER TABLE `about` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `officeCategoryID` smallint(3) unsigned NOT NULL,
  `officeLocale` varchar(40) NOT NULL DEFAULT '',
  `companyName` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `city` varchar(40) NOT NULL DEFAULT '',
  `stateID` tinyint(2) unsigned NOT NULL DEFAULT '51',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(40) NOT NULL DEFAULT '',
  `contact1FirstName` varchar(40) NOT NULL DEFAULT '',
  `contact1LastName` varchar(40) NOT NULL DEFAULT '',
  `contact2FirstName` varchar(40) NOT NULL DEFAULT '',
  `contact2LastName` varchar(40) NOT NULL DEFAULT '',
  `contact3FirstName` varchar(40) NOT NULL DEFAULT '',
  `contact3LastName` varchar(40) NOT NULL DEFAULT '',
  `phone` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `websiteURL` varchar(2083) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `stateID` (`stateID`),
  KEY `country` (`country`),
  KEY `companyName` (`companyName`),
  KEY `officeLocale` (`officeLocale`),
  KEY `officeCategoryID` (`officeCategoryID`),
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`stateID`) REFERENCES `states` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `contacts_ibfk_3` FOREIGN KEY (`officeCategoryID`) REFERENCES `officecategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;

INSERT INTO `contacts` (`id`, `officeCategoryID`, `officeLocale`, `companyName`, `address1`, `address2`, `address3`, `city`, `stateID`, `zip`, `country`, `contact1FirstName`, `contact1LastName`, `contact2FirstName`, `contact2LastName`, `contact3FirstName`, `contact3LastName`, `phone`, `email`, `websiteURL`)
VALUES
	(1,1,'New York','Grand Large Inc.','54 Mercer Street','','','New York',32,'10013','USA','Steven','Horton','','','','','2129888390','steven@grandlargeinc.com','www.grandlargeinc.com'),
	(2,1,'Paris','Grand Large Inc.','22 Rue de Navarin','','','Paris',51,'75009','France','Steven','Horton','','','','','33143060330','info@grandlargeinc.com','www.grandlargeinc.com'),
	(3,2,'East Coast','Fm Artist Management','30 Irving Place','6th Floor','','New York',32,'10013','USA','Carl','Forsberg','Marianne','McCarley','','','2125812200','carl@fmartist.com','www.forsbergmccarley.com'),
	(4,2,'Midwest','Hilly Reps','680 North Lake Shore Drive','Suite 320','','Chicago',13,'60611','USA','Hillary','Herbst','Laurel','Dobose','','','3129441100','hillary@hillyreps.com','www.hillyreps.com'),
	(5,1,'West Coast','Grand Large Inc.','21816 Grovepark Drive','','','Santa Clarita',5,'91350','USA','Jonathan','Miller','Executive','Producer','','','2123008962','jonathan@grandlargeinc.com','www.grandlargeinc.com');

/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table directors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `directors`;

CREATE TABLE `directors` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `bio` text,
  `websiteURL` varchar(2083) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `firstName` (`firstName`),
  KEY `lastName` (`lastName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `directors` WRITE;
/*!40000 ALTER TABLE `directors` DISABLE KEYS */;

INSERT INTO `directors` (`id`, `active`, `firstName`, `lastName`, `bio`, `websiteURL`, `description`)
VALUES
	(1,1,'Tracey','Rowe','Tracey\'s work is known for compelling rich images and stylish details. She thrives on bringing the emotional content out of each visual while at the same time enhancing the concept of the film.','http://www.traceyrowe.com',''),
	(2,1,'Gaysorn','Thavat','Gaysorn\'s work has a distinctive and smart humor infused with a stylish and fresh approach.  From drama to comedy, she is recognized for her strong visuals, inspiring performances and passion for storytelling.\n\nHer commercial titled \"Dying Old\" for the Breast Cancer Research Trust in New Zealand was awarded a Gold Lion at the Cannes International Advertising Festival 2009.','',''),
	(3,1,'Jean-Pierre','Jeunet','Jean-Pierre Jeunet is the writer/director behind the wonderfully imaginative films <em>Am&eacute;lie, The City Of Lost Children, and Delicatessen</em>. He began his career in France shooting television commercials, music videos and short films.','http://www.jpjeunet-siteofficiel.com',''),
	(5,1,'Fred','Garson','Fred Garson is a commercial and feature film director who has a very strong eye for what is modern and fresh.  He is also heavily involved in the music industry and brings an enormous energy and style to all his films.','',''),
	(6,1,'Tom','Kan','Tom Kan is a French born and raised Japanese national based in Paris. He has directed commercials as well as music videos for iconic French artists Daft Punk, Ophelie Winter and AIR. Tom is also known for his design work, having done logos, album covers and movie titles.','http://www.tomkandesign.com',''),
	(7,1,'Bettina','Rheims','Bettina is renowned for her erotic black and white portraits of women as well as for working with famous actresses such as Catherine Deneuve, Charlotte Rampling, Lauren Bacall, Glenn Close, Daryl Hannah and Sharon Stone. Her film style is intrinsically modern, sensual, intricate and relevant.','',''),
	(8,1,'Serge','Guerand','Serge Guerand is a French based photographer as well as a commercial and music video director.  He has a warm sensibility and an amazing ability to connect with his actors to bring out strong performances, especially with children.','http://www.guerand.com',''),
	(9,1,'Liz','Hinlein','Liz Hinlein is an award-winning director, having directed national television spots for Clairol, Revlon, Almay, Maybelline, Wella and Nivea, among others.','',''),
	(10,1,'Mark','Tiedemann','New York based Mark Tiedemann is an accomplished comedic director for television and the Internet.  He has directed commercials for Staples, Chevy, McDonalds, Sony Playstation, Kia, Dish TV, Campbell\'s, Lysol, Schick, Motorola, Ikea, ATT, Baby Ruth and Verizon.','',''),
	(11,1,'Marc','Caro','Marc Caro, is a French filmmaker and illustrator, best known for his co-directing projects, <em>Delicatessen</em> and <em>City of Lost Children</em>.  He is noted for his creative character design, which is present in his latest sci-fi inspired feature film <em>Dante 01</em>.','',''),
	(12,1,'Tran anh','Hung','French-Vietnamese director, Tran Anh Hung has a strong eye for color, texture and beauty.  His Oscar-nominated debut (for Best foreign film) was with <em>The Scent of Green Papaya</em>, which also won two top prizes at the prestigious 1993 Cannes Film Festival.','',''),
	(13,1,'Showreel','','','','grand large showreel'),
	(16,1,'Ronald','Wohlman','Director Ronald Wohlman has enjoyed an eclectic advertising career that\'s seen him living in Johannesburg, Toronto, New York, Paris and now London.\n\nHe is passionate about different cultures and is fueled by all things creative â€“ art, photography, food, fashion, design, literature, music and film.  A true internationalist, Martin is fortunate to travel extensively, always drawing on his experiences to color his work.','',''),
	(17,1,'Shizue','','Shizue is an American director who has lived and worked in Asia, Australia, the U.S., and is now based in Europe.\n\nInitially graduating with a degree in architecture, Shizue realized film to be her true love and the synthesis of all her interests.\n\nHer short films range from a documentary on Jackie Chan to a contemporary take on Little Red Riding Hood, which have aired on television stations throughout Europe.\n\nIn 2000, Shizue moved to Vienna, Austria where she has been directing commercials for international clients ever since.  Her individual approach and feminine sensibilities are expressed in images that are stylish and sensual, always capturing the moment.','',''),
	(18,1,'Leif Husted-Jensen','','Leif-Husted Jensen is an accomplished commercial director and cinematographer based in New York City.  As a filmmaker, he has been drawn to storytelling and specializes in documentary media.\n\nWith his strong sense of visual composition and ability to connect with people, Leif constantly strives to create performances that are both compelling and truly original.','',''),
	(19,1,'Andy','Tennant','Andy Tennant is an American screenwriter and director that made his way into film through acting and television directing. \n\nHe is credited for his work on the worldwide blockbuster Hitch, starring Will Smith, along with the popular romantic comedies Sweet Home Alabama, starring Reese Witherspoon, The Bounty Hunter and Fool\'s Gold.\n\nHe also directed Ever After, a film inspired by the fairy tale Cinderella, starring Drew Barrymore and Angelica Huston and the classic Anna and the King, with Jodie Foster and Chow Yun-Fat, which received two Academy Award nominations.','',''),
	(24,1,'Tall Black','Girls','Way back in the early days of 2010, on a dilapidated street in Brooklyn, Tall Black Girls was conceived. Like most pregnancies, it was the result of bathtub gin and vulnerability. \"The Girls\" formed with a simple mission and purpose: to create a one stop creative shop that produces films for a variety of clients and genres. The final product to be high on style and low on barriers. \n\nThe Girls specialize in managing projects beginning to end. From production to post-production and whatever there is in-between. But please, don\'t just take this bio\'s word, it\'s meant to talk them up. Meet them, talk to them, let them buy you a drink. In the morning you\'ll be completely free of guilt.','',''),
	(25,1,'Kanye','West','','http://www.kanyewest.com/','');

/*!40000 ALTER TABLE `directors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `file` varchar(40) NOT NULL,
  `forUserId` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forUserId` (`forUserId`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`forUserId`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table mediaCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mediaCategories`;

CREATE TABLE `mediaCategories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `navId` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `navId` (`navId`),
  CONSTRAINT `mediacategories_ibfk_1` FOREIGN KEY (`navId`) REFERENCES `navigation` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mediaCategories` WRITE;
/*!40000 ALTER TABLE `mediaCategories` DISABLE KEYS */;

INSERT INTO `mediaCategories` (`id`, `navId`)
VALUES
	(1,1),
	(2,2),
	(3,3),
	(4,4),
	(5,5),
	(6,6);

/*!40000 ALTER TABLE `mediaCategories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mediaFeeds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mediaFeeds`;

CREATE TABLE `mediaFeeds` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mediaCategoryID` int(11) unsigned NOT NULL,
  `categoryPosition` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `directorID` int(11) unsigned NOT NULL DEFAULT '0',
  `feedURL` varchar(2083) NOT NULL,
  `timeAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `mediaCategoryID` (`mediaCategoryID`),
  KEY `directorID` (`directorID`),
  CONSTRAINT `mediafeeds_ibfk_1` FOREIGN KEY (`directorID`) REFERENCES `directors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mediafeeds_ibfk_2` FOREIGN KEY (`mediaCategoryID`) REFERENCES `mediacategories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `mediaFeeds` WRITE;
/*!40000 ALTER TABLE `mediaFeeds` DISABLE KEYS */;

INSERT INTO `mediaFeeds` (`id`, `mediaCategoryID`, `categoryPosition`, `directorID`, `feedURL`, `timeAdded`, `lastUpdated`)
VALUES
	(1,1,1,13,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/8fecac0a24cf58794b1954d338b641aa/','2011-01-03 23:49:03','0000-00-00 00:00:00'),
	(2,2,1,1,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/760dd15520de190a03949d5cd60cd065/','2011-01-03 23:51:09','0000-00-00 00:00:00'),
	(3,2,2,2,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/af079faf9d205592723c7fa09cb6a101/','2011-01-03 23:51:52','0000-00-00 00:00:00'),
	(4,2,3,3,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/e563367bd2ccfb3d63108e728730e69b/','2011-01-03 23:52:13','0000-00-00 00:00:00'),
	(6,2,5,5,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/2023b53d7fe5cbb0f5cab5827a6f105e/','2011-01-03 23:52:46','0000-00-00 00:00:00'),
	(7,2,6,6,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/f0c36fd382d5895cff4c05359929a66e/','2011-01-03 23:52:58','0000-00-00 00:00:00'),
	(8,3,1,7,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/f51b9282a8cb64eec0b519b3ea47080d/','2011-01-03 23:53:36','0000-00-00 00:00:00'),
	(9,3,2,8,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/16eb32b9ffb3f055cc49c20257b0e892/','2011-01-03 23:53:44','0000-00-00 00:00:00'),
	(10,4,1,9,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/8d9782a078190646548445a4db4604c9/','2011-01-03 23:54:49','0000-00-00 00:00:00'),
	(11,4,2,10,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/00eb5062d6d6b807421ace679ad0af31/','2011-01-03 23:54:57','0000-00-00 00:00:00'),
	(12,5,1,3,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/e563367bd2ccfb3d63108e728730e69b/','2011-01-03 23:55:25','0000-00-00 00:00:00'),
	(13,5,2,11,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/2838ddc0a0a766181709e98d178b0267/','2011-01-03 23:55:30','0000-00-00 00:00:00'),
	(14,5,3,12,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/e99ac277129794a1147304223b99d247/','2011-01-03 23:55:37','0000-00-00 00:00:00'),
	(20,6,2,16,'http://www.wdcdn.net/rss/presentation/library/client/glx/id/319800429084791d370a9733e4861d9a/','2011-02-05 15:51:16','0000-00-00 00:00:00'),
	(21,3,3,17,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/764f64738132ac5166b772ffe733d4b9/','2011-02-05 15:55:15','0000-00-00 00:00:00'),
	(22,6,3,18,'http://www.wdcdn.net/rss/presentation/library/client/glx/id/a9e971d1fd2f5d6ee38575437e8024a4/','2011-03-06 16:56:28','0000-00-00 00:00:00'),
	(24,2,4,19,'http://www.wdcdn.net/rss/presentation/library/client/grandlarge/id/b1a2d0ec17d09b41d89aaae557e20ae8/','2011-07-03 18:11:16','0000-00-00 00:00:00'),
	(52,6,1,24,'http://www.wdcdn.net/rss/presentation/library/client/glx/id/ac03c9a256442fa8a8349d353989b644','2011-07-17 23:50:50','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `mediaFeeds` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table navigation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `navigation`;

CREATE TABLE `navigation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `navigation` WRITE;
/*!40000 ALTER TABLE `navigation` DISABLE KEYS */;

INSERT INTO `navigation` (`id`, `name`)
VALUES
	(1,'Showreel'),
	(2,'Commercials'),
	(3,'Beauty'),
	(4,'International'),
	(5,'Feature Film'),
	(6,'GL-X'),
	(7,'Notable'),
	(8,'About'),
	(9,'Contact');

/*!40000 ALTER TABLE `navigation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `image` varchar(32) NOT NULL,
  `desc` text NOT NULL,
  `url` varchar(2083) NOT NULL,
  `timeAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `timeAdded` (`timeAdded`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;

INSERT INTO `news` (`id`, `title`, `image`, `desc`, `url`, `timeAdded`)
VALUES
	(1,'BUF','buf.jpg','BUF','http://www.robbersdog.co.nz','2011-01-10 13:01:09'),
	(2,'Walkabout Films','walkabout.jpg','Walkabout Films','http://www.walkaboutfilms.comau.com','2011-01-10 12:02:15'),
	(5,'Mac Guff','mac_guff.jpg','Mac Guff','http://www.macguff.com','2011-01-10 11:02:44'),
	(4,'Animal Logic','animal_logic.jpg','Animal Logic','http://www.animallogic.com','2011-01-10 10:03:41'),
	(3,'Robber\'s Dog','robbers_dog.jpg','Robbers Dog','http://www.robbersdog.co.nz','2011-01-10 09:03:44'),
	(7,'Tom Kan Design','tom_kan.jpg','Tom Kan Design','http://www.tomkandesign.com','2011-01-10 08:05:00'),
	(6,'Francesco Carrozzini','francesco_carrozzini.jpg','Francesco Carrozzini','http://www.francescocarrozzini.com','2011-01-10 07:06:03'),
	(8,'Rockwood Music Hall','rockwood_music_hall.jpg','Rockwood Music Hall','http://www.rockwoodmusichall.com','2011-01-10 06:50:32'),
	(9,'Cosmic','cosmic.jpg','Cosmic','http://www.cosmicparis.com/#/bruno_delbonnel','2011-01-10 06:40:04'),
	(10,'Piffeteau','piffeteau.jpg','Piffeteau','http://www.piffeteau.com','2011-01-10 06:30:33'),
	(11,'Kinou','kinou.jpg','Kinou','http://www.kinou.fr','2011-01-10 06:25:53'),
	(12,'Nicolas Loir','nicolas_loir.jpg','Nicolas Loir','http://www.nicolasloir.com','2011-01-10 06:20:21'),
	(13,'World Locations','world_locations.jpg','World Locations','http://www.worldlocations.com','2011-01-10 06:15:43'),
	(14,'G2 Works','g2_works.jpg','G2 Works','http://www.g2works.com','2011-01-10 06:10:44'),
	(15,'Franck Tymezuk','francky_tymezuk.jpg','Franck Tymezuk','http://www.francktymezuk.com','2011-01-10 06:06:06');

/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table officeCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `officeCategories`;

CREATE TABLE `officeCategories` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `officeCategories` WRITE;
/*!40000 ALTER TABLE `officeCategories` DISABLE KEYS */;

INSERT INTO `officeCategories` (`id`, `name`)
VALUES
	(1,'Offices'),
	(2,'Sales');

/*!40000 ALTER TABLE `officeCategories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table states
# ------------------------------------------------------------

DROP TABLE IF EXISTS `states`;

CREATE TABLE `states` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `abbreviation` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;

INSERT INTO `states` (`id`, `abbreviation`)
VALUES
	(1,'AL'),
	(2,'AK'),
	(3,'AZ'),
	(4,'AR'),
	(5,'CA'),
	(6,'CO'),
	(7,'CT'),
	(8,'DE'),
	(9,'FL'),
	(10,'GA'),
	(11,'HI'),
	(12,'ID'),
	(13,'IL'),
	(14,'IN'),
	(15,'IA'),
	(16,'KS'),
	(17,'KY'),
	(18,'LA'),
	(19,'ME'),
	(20,'MD'),
	(21,'MA'),
	(22,'MI'),
	(23,'MN'),
	(24,'MS'),
	(25,'MO'),
	(26,'MT'),
	(27,'NE'),
	(28,'NV'),
	(29,'NH'),
	(30,'NJ'),
	(31,'NM'),
	(32,'NY'),
	(33,'NC'),
	(34,'ND'),
	(35,'OH'),
	(36,'OK'),
	(37,'OR'),
	(38,'PA'),
	(39,'RI'),
	(40,'SC'),
	(41,'SD'),
	(42,'TN'),
	(43,'TX'),
	(44,'UT'),
	(45,'VT'),
	(46,'VA'),
	(47,'WA'),
	(48,'WV'),
	(49,'WI'),
	(50,'WY'),
	(51,''),
	(52,'DC'),
	(53,'PR');

/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userTypeId` tinyint(2) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `key` (`key`),
  KEY `userTypeId` (`userTypeId`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`userTypeId`) REFERENCES `usertypes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `userTypeId`, `email`, `key`)
VALUES
	(1,3,'sidekick2rida@gmail.com','123456'),
	(2,2,'steven@grandlargeinc.com','123456'),
	(3,2,'george@grandlargeinc.com','123456'),
	(4,2,'kara@grandlargeinc.com','123456');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table userTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userTypes`;

CREATE TABLE `userTypes` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `accessLevel` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accessLevel` (`accessLevel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `userTypes` WRITE;
/*!40000 ALTER TABLE `userTypes` DISABLE KEYS */;

INSERT INTO `userTypes` (`id`, `name`, `accessLevel`)
VALUES
	(1,'client',1),
	(2,'admin',2),
	(3,'architect',3);

/*!40000 ALTER TABLE `userTypes` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
