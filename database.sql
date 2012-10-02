-- MySQL dump 10.11
--
-- Host: localhost    Database: track
-- ------------------------------------------------------
-- Server version	5.0.45-community-nt

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
-- Table structure for table `acos`
--

CREATE DATABASE IF NOT EXISTS `tracker`;

USE `tracker`;

DROP TABLE IF EXISTS `acos`;
CREATE TABLE `acos` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acos`
--

LOCK TABLES `acos` WRITE;
/*!40000 ALTER TABLE `acos` DISABLE KEYS */;
INSERT INTO `acos` VALUES (1,NULL,NULL,NULL,'controllers',1,260),(2,1,'Pages',NULL,'Pages',2,17),(3,2,'Pages',NULL,'display',3,4),(4,2,'Pages',NULL,'updateJSONFile',5,6),(5,2,'Pages',NULL,'add',7,8),(6,2,'Pages',NULL,'edit',9,10),(7,2,'Pages',NULL,'index',11,12),(8,2,'Pages',NULL,'view',13,14),(9,2,'Pages',NULL,'delete',15,16),(10,1,'Drugs',NULL,'Drugs',18,29),(11,10,'Drugs',NULL,'index',19,20),(12,10,'Drugs',NULL,'view',21,22),(13,10,'Drugs',NULL,'add',23,24),(14,10,'Drugs',NULL,'edit',25,26),(15,10,'Drugs',NULL,'delete',27,28),(16,1,'DrugsKittypes',NULL,'DrugsKittypes',30,41),(17,16,'DrugsKittypes',NULL,'index',31,32),(18,16,'DrugsKittypes',NULL,'add',33,34),(19,16,'DrugsKittypes',NULL,'edit',35,36),(20,16,'DrugsKittypes',NULL,'delete',37,38),(21,16,'DrugsKittypes',NULL,'view',39,40),(22,1,'DrugsTreatments',NULL,'DrugsTreatments',42,53),(23,22,'DrugsTreatments',NULL,'index',43,44),(24,22,'DrugsTreatments',NULL,'add',45,46),(25,22,'DrugsTreatments',NULL,'edit',47,48),(26,22,'DrugsTreatments',NULL,'delete',49,50),(27,22,'DrugsTreatments',NULL,'view',51,52),(28,1,'Groups',NULL,'Groups',54,65),(29,28,'Groups',NULL,'index',55,56),(30,28,'Groups',NULL,'view',57,58),(31,28,'Groups',NULL,'add',59,60),(32,28,'Groups',NULL,'edit',61,62),(33,28,'Groups',NULL,'delete',63,64),(34,1,'Kits',NULL,'Kits',66,77),(35,34,'Kits',NULL,'index',67,68),(36,34,'Kits',NULL,'view',69,70),(37,34,'Kits',NULL,'add',71,72),(38,34,'Kits',NULL,'edit',73,74),(39,34,'Kits',NULL,'delete',75,76),(40,1,'Kittypes',NULL,'Kittypes',78,89),(41,40,'Kittypes',NULL,'index',79,80),(42,40,'Kittypes',NULL,'view',81,82),(43,40,'Kittypes',NULL,'add',83,84),(44,40,'Kittypes',NULL,'edit',85,86),(45,40,'Kittypes',NULL,'delete',87,88),(46,1,'Levels',NULL,'Levels',90,101),(47,46,'Levels',NULL,'index',91,92),(48,46,'Levels',NULL,'view',93,94),(49,46,'Levels',NULL,'add',95,96),(50,46,'Levels',NULL,'edit',97,98),(51,46,'Levels',NULL,'delete',99,100),(52,1,'Locations',NULL,'Locations',102,113),(53,52,'Locations',NULL,'index',103,104),(54,52,'Locations',NULL,'view',105,106),(55,52,'Locations',NULL,'add',107,108),(56,52,'Locations',NULL,'edit',109,110),(57,52,'Locations',NULL,'delete',111,112),(58,1,'Patients',NULL,'Patients',114,125),(59,58,'Patients',NULL,'index',115,116),(60,58,'Patients',NULL,'view',117,118),(61,58,'Patients',NULL,'add',119,120),(62,58,'Patients',NULL,'edit',121,122),(63,58,'Patients',NULL,'delete',123,124),(64,1,'Phones',NULL,'Phones',126,137),(65,64,'Phones',NULL,'index',127,128),(66,64,'Phones',NULL,'view',129,130),(67,64,'Phones',NULL,'add',131,132),(68,64,'Phones',NULL,'edit',133,134),(69,64,'Phones',NULL,'delete',135,136),(70,1,'Rawreports',NULL,'Rawreports',138,149),(71,70,'Rawreports',NULL,'index',139,140),(72,70,'Rawreports',NULL,'view',141,142),(73,70,'Rawreports',NULL,'add',143,144),(74,70,'Rawreports',NULL,'edit',145,146),(75,70,'Rawreports',NULL,'delete',147,148),(76,1,'Stats',NULL,'Stats',150,171),(77,76,'Stats',NULL,'index',151,152),(78,76,'Stats',NULL,'view',153,154),(79,76,'Stats',NULL,'add',155,156),(80,76,'Stats',NULL,'edit',157,158),(81,76,'Stats',NULL,'delete',159,160),(82,76,'Stats',NULL,'update_select',161,162),(83,76,'Stats',NULL,'updateJSONFile',163,164),(84,76,'Stats',NULL,'sdrugs',165,166),(85,76,'Stats',NULL,'streatments',167,168),(86,76,'Stats',NULL,'options',169,170),(87,1,'Statuses',NULL,'Statuses',172,183),(88,87,'Statuses',NULL,'index',173,174),(89,87,'Statuses',NULL,'view',175,176),(90,87,'Statuses',NULL,'add',177,178),(91,87,'Statuses',NULL,'edit',179,180),(92,87,'Statuses',NULL,'delete',181,182),(93,1,'Tracks',NULL,'Tracks',184,211),(94,93,'Tracks',NULL,'index',185,186),(95,93,'Tracks',NULL,'view',187,188),(96,93,'Tracks',NULL,'add',189,190),(97,93,'Tracks',NULL,'edit',191,192),(98,93,'Tracks',NULL,'delete',193,194),(99,93,'Tracks',NULL,'delivery',195,196),(100,93,'Tracks',NULL,'update_select',197,198),(101,93,'Tracks',NULL,'update_parent',199,200),(102,93,'Tracks',NULL,'update_patient',201,202),(103,93,'Tracks',NULL,'update_status',203,204),(104,93,'Tracks',NULL,'sdrugs',205,206),(105,93,'Tracks',NULL,'streatments',207,208),(106,93,'Tracks',NULL,'options',209,210),(107,1,'Treatments',NULL,'Treatments',212,223),(108,107,'Treatments',NULL,'index',213,214),(109,107,'Treatments',NULL,'view',215,216),(110,107,'Treatments',NULL,'add',217,218),(111,107,'Treatments',NULL,'edit',219,220),(112,107,'Treatments',NULL,'delete',221,222),(113,1,'Users',NULL,'Users',224,247),(114,113,'Users',NULL,'login',225,226),(115,113,'Users',NULL,'logout',227,228),(116,113,'Users',NULL,'index',229,230),(117,113,'Users',NULL,'view',231,232),(118,113,'Users',NULL,'add',233,234),(119,113,'Users',NULL,'edit',235,236),(120,113,'Users',NULL,'delete',237,238),(121,113,'Users',NULL,'initDB',239,240),(122,113,'Users',NULL,'build_acl',241,242),(123,113,'Users',NULL,'changePass',243,244),(124,113,'Users',NULL,'resetUsers',245,246),(125,1,'Views',NULL,'Views',248,259),(126,125,'Views',NULL,'add',249,250),(127,125,'Views',NULL,'edit',251,252),(128,125,'Views',NULL,'index',253,254),(129,125,'Views',NULL,'view',255,256),(130,125,'Views',NULL,'delete',257,258);
/*!40000 ALTER TABLE `acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
CREATE TABLE `aros` (
  `id` int(10) NOT NULL auto_increment,
  `parent_id` int(10) default NULL,
  `model` varchar(255) default NULL,
  `foreign_key` int(10) default NULL,
  `alias` varchar(255) default NULL,
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aros`
--

LOCK TABLES `aros` WRITE;
/*!40000 ALTER TABLE `aros` DISABLE KEYS */;
INSERT INTO `aros` VALUES (1,NULL,'Group',8,NULL,1,6),(2,NULL,'Group',9,NULL,7,10),(3,NULL,'Group',10,NULL,11,14),(4,1,'User',1,NULL,2,3),(5,2,'User',2,NULL,8,9),(7,1,'User',4,NULL,4,5),(19,3,'User',6,NULL,12,13);
/*!40000 ALTER TABLE `aros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE `aros_acos` (
  `id` int(10) NOT NULL auto_increment,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL default '0',
  `_read` varchar(2) NOT NULL default '0',
  `_update` varchar(2) NOT NULL default '0',
  `_delete` varchar(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aros_acos`
--

LOCK TABLES `aros_acos` WRITE;
/*!40000 ALTER TABLE `aros_acos` DISABLE KEYS */;
INSERT INTO `aros_acos` VALUES (1,1,1,'1','1','1','1'),(2,2,1,'1','1','1','1'),(3,2,83,'1','1','1','1'),(4,2,84,'1','1','1','1'),(5,2,85,'1','1','1','1'),(6,2,77,'1','1','1','1'),(7,2,78,'1','1','1','1'),(8,2,10,'1','1','1','1'),(9,2,107,'1','1','1','1'),(10,2,22,'1','1','1','1'),(11,2,52,'1','1','1','1'),(12,2,64,'1','1','1','1'),(13,2,71,'1','1','1','1'),(14,2,72,'1','1','1','1'),(15,2,115,'1','1','1','1'),(16,2,86,'1','1','1','1'),(17,2,123,'1','1','1','1'),(18,2,113,'-1','-1','-1','-1'),(19,2,28,'-1','-1','-1','-1'),(20,2,121,'-1','-1','-1','-1'),(21,2,122,'-1','-1','-1','-1'),(22,2,76,'-1','-1','-1','-1'),(23,2,70,'-1','-1','-1','-1'),(24,3,1,'1','1','1','1'),(25,3,83,'1','1','1','1'),(26,3,84,'1','1','1','1'),(27,3,85,'1','1','1','1'),(28,3,115,'1','1','1','1'),(29,3,12,'1','1','1','1'),(30,3,109,'1','1','1','1'),(31,3,54,'1','1','1','1'),(32,3,123,'1','1','1','1'),(33,3,113,'-1','-1','-1','-1'),(34,3,28,'-1','-1','-1','-1'),(35,3,121,'-1','-1','-1','-1'),(36,3,122,'-1','-1','-1','-1'),(37,3,10,'-1','-1','-1','-1'),(38,3,107,'-1','-1','-1','-1'),(39,3,52,'-1','-1','-1','-1'),(40,3,64,'-1','-1','-1','-1'),(41,3,76,'-1','-1','-1','-1'),(42,3,70,'-1','-1','-1','-1'),(43,3,22,'-1','-1','-1','-1'),(44,3,86,'-1','-1','-1','-1');
/*!40000 ALTER TABLE `aros_acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
CREATE TABLE `drugs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `code` varchar(3) NOT NULL,
  `presentation` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drugs`
--

LOCK TABLES `drugs` WRITE;
/*!40000 ALTER TABLE `drugs` DISABLE KEYS */;
INSERT INTO `drugs` VALUES (1,'Mectizan','IVM','Tablets'),(2,'Albendazole','ALB','Tablets'),(3,'Mebendazole','MEB','Tablets'),(4,'Praziquantel','PZQ','Tablets'),(5,'Zithromax tablets','ZTT','Tablets'),(6,'Zithromax pediatric','ZTS','Oral Suspension');
/*!40000 ALTER TABLE `drugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drugs_kittypes`
--

DROP TABLE IF EXISTS `drugs_kittypes`;
CREATE TABLE `drugs_kittypes` (
  `id` int(11) NOT NULL auto_increment,
  `drug_id` int(11) default NULL,
  `kittype_id` int(11) default NULL,
  `quantity` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drugs_kittypes`
--

LOCK TABLES `drugs_kittypes` WRITE;
/*!40000 ALTER TABLE `drugs_kittypes` DISABLE KEYS */;
INSERT INTO `drugs_kittypes` VALUES (1,1,1,NULL),(2,2,1,NULL);
/*!40000 ALTER TABLE `drugs_kittypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drugs_treatments`
--

DROP TABLE IF EXISTS `drugs_treatments`;
CREATE TABLE `drugs_treatments` (
  `id` int(11) NOT NULL auto_increment,
  `drug_id` int(11) NOT NULL,
  `treatment_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drugs_treatments`
--

LOCK TABLES `drugs_treatments` WRITE;
/*!40000 ALTER TABLE `drugs_treatments` DISABLE KEYS */;
INSERT INTO `drugs_treatments` VALUES (1,5,19,12),(3,5,10,52),(4,2,10,14),(18,4,10,5555),(19,3,10,44441),(60,6,12,89),(61,3,12,125),(70,1,1,8),(72,3,4,78),(73,6,4,123);
/*!40000 ALTER TABLE `drugs_treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (8,'administrators','2010-08-29 23:47:59','2010-08-30 14:48:47'),(9,'moderators','2010-08-29 23:48:10','2010-08-30 14:48:54'),(10,'users','2010-08-29 23:48:18','2010-08-30 14:49:02');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kits`
--

DROP TABLE IF EXISTS `kits`;
CREATE TABLE `kits` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(6) default NULL,
  `kittype_id` int(11) default NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `code_2` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kits`
--

LOCK TABLES `kits` WRITE;
/*!40000 ALTER TABLE `kits` DISABLE KEYS */;
INSERT INTO `kits` VALUES (52,'12111',1,'2011-06-29 12:23:17'),(53,'22215',1,'2011-06-29 14:21:35');
/*!40000 ALTER TABLE `kits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kittypes`
--

DROP TABLE IF EXISTS `kittypes`;
CREATE TABLE `kittypes` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kittypes`
--

LOCK TABLES `kittypes` WRITE;
/*!40000 ALTER TABLE `kittypes` DISABLE KEYS */;
INSERT INTO `kittypes` VALUES (1,'my kit');
/*!40000 ALTER TABLE `kittypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(35) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (10,'DCC'),(20,'CH'),(30,'PHC'),(40,'RH');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(70) NOT NULL,
  `shortname` varchar(4) NOT NULL,
  `locationLatitude` varchar(13) NOT NULL,
  `locationLongitude` varchar(13) NOT NULL,
  `deleted` int(1) default '0',
  `level_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Seshego District Hospital','','-23.844362','29.389459',0,30),(3,'Vredenburg Hospital','fdfd','-32.905648','17.988911',0,10),(5,'Hospital 5','rew','-32.905648','19.905648',0,10),(6,'test location','t lo','-15.314857','32.907714',0,20),(7,'test','tst','-15.313857','32.907714',1,NULL),(8,'test','tst','-15.313857','32.907714',1,NULL),(9,'test','test','-15.313857','32.907714',0,20),(10,'Referral','ref','-15.314657','32.907614',0,40);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients` (
  `id` int(11) NOT NULL auto_increment,
  `number` varchar(35) default NULL,
  `created` datetime default NULL,
  `consent` int(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pateintnumberuniq` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,'3234321',NULL,0),(2,'324343',NULL,1);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
CREATE TABLE `phones` (
  `id` int(11) NOT NULL auto_increment,
  `phonenumber` varchar(12) default NULL,
  `active` int(1) NOT NULL,
  `location_id` int(11) default NULL,
  `name` varchar(30) NOT NULL,
  `deleted` int(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
INSERT INTO `phones` VALUES (3,'0724008227',1,1,'L2',0),(4,'0724008228',1,6,'L3',0),(5,'0724008229',0,1,'L4',0),(6,'0724008230',1,3,'L5',0),(8,'0724008231',1,3,'L6',0),(11,'55555',1,1,'L7',0),(12,'555556',1,5,'my phone12',0),(13,'0785656668',1,1,'testing',0),(15,'123456790',1,1,'my phone 89',0),(22,'+2724008226',1,5,'lachko1',0),(23,'+2772400822',0,NULL,'',1),(27,'+27724008226',1,1,'lk',0),(28,'+19193604046',1,6,'ljkkkkkkkkkk',0),(32,'+1919360',1,3,'test',NULL),(33,'123434',0,3,'test',NULL);
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `create_flsms_contact` AFTER INSERT ON `phones` FOR EACH ROW BEGIN
	DECLARE contactid integer;
	
	INSERT INTO flsms.contact (active, name, phoneNumber) 
				VALUES (NEW.active, NEW.name, NEW.phonenumber);
	
	SELECT contact_id INTO contactid from flsms.contact WHERE
						name = NEW.name and phoneNumber = NEW.phonenumber;
	INSERT INTO flsms.groupmembership (contact_contact_id, group_path) 
							
							VALUES (contactid, '/Testers');
END */;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `update_flsms_contact` AFTER UPDATE ON `phones` FOR EACH ROW BEGIN
	UPDATE flsms.contact set active = NEW.active, 
							name = NEW.name, 
							phoneNumber = NEW.phonenumber
							WHERE phoneNumber = OLD.phonenumber;
END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;

--
-- Table structure for table `rawreports`
--

DROP TABLE IF EXISTS `rawreports`;
CREATE TABLE `rawreports` (
  `id` int(11) NOT NULL auto_increment,
  `raw_message` varchar(160) NOT NULL,
  `message_code` varchar(90) NOT NULL,
  `created` datetime NOT NULL,
  `phone_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3907 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rawreports`
--

LOCK TABLES `rawreports` WRITE;
/*!40000 ALTER TABLE `rawreports` DISABLE KEYS */;
INSERT INTO `rawreports` VALUES (46,'absc 78 0724008230','OK','2010-08-31 11:20:06',12),(47,'meb 4 0724008230','OK','2010-08-31 11:20:47',6),(48,'ivm 125 0724008230','OK','2010-08-31 11:21:08',6),(49,'abcd 92 0724008230','OK','2010-08-31 11:21:58',6),(50,'ivm 12 0724008229','OK','2010-08-31 11:23:05',5),(51,'ivm 12 0724008231','OK','2010-08-31 11:25:04',8),(52,'abcd 85 0724008231','OK','2010-08-31 11:25:39',8),(53,'abcd 52 0724008226','OK','2010-08-31 11:26:05',2),(54,'abcd po 0724008226','Quantity must be numeric.','2010-08-31 11:34:46',2),(55,'abcdp 51 0724008226','Drug/Treatment code incorrect.','2010-08-31 11:34:56',2),(56,'a 51 0724008226','Drug/Treatment code incorrect.','2010-08-31 11:35:06',2),(57,'51 0724008226 ','Incompelete argument set.','2010-08-31 11:35:22',-1),(58,'absc 63 0724008226','OK','2010-08-31 12:24:03',2),(59,'ivm 63 0724008226','OK','2010-08-31 12:25:41',2),(60,'ivm 47 0724008226','OK','2010-08-31 12:26:25',2),(61,'alb 152 0724008227','OK','2010-08-31 12:27:23',3),(62,'alb 151 0724008227','OK','2010-08-31 12:33:06',3),(63,'abcd po 0724008226','Quantity must be numeric.','2010-08-31 12:34:19',2),(64,'abcd 8 0724008226','OK','2010-08-31 12:34:26',2),(65,'abcd 18 0724008227','OK','2010-08-31 12:35:00',3),(66,'abcd 19 0724008228','OK','2010-08-31 12:35:25',4),(67,'abcd 29 0724008229','OK','2010-08-31 12:35:34',5),(68,'abcd 30 0724008230','OK','2010-08-31 12:36:20',6),(69,'zts 74 0724008230','OK','2010-08-31 12:36:52',6),(70,'ivm 253 0724008231','OK','2010-08-31 13:23:51',8),(71,'abcd 25 0724008231','OK','2010-08-31 13:24:03',8),(72,'ABCD 25 0724008231','OK','2010-09-30 04:27:47',8),(73,'ABCD 151 0724008231','OK','2010-09-30 04:29:48',8),(74,'meb 6 0724008226','OK','2010-09-30 04:36:50',2),(75,'meb 6 0724008226','OK','2010-09-30 04:48:37',2),(76,'meb 9 0724008226','OK','2010-09-30 05:05:46',2),(77,'meb 9 0724008226','OK','2010-09-30 05:06:16',2),(78,'meb 9 0724008226','OK','2010-09-30 05:06:38',2),(79,'meb 9 0724008226','OK','2010-09-30 05:07:59',2),(80,'meb 10 0724008226','OK','2010-09-30 05:09:31',2),(81,'meb 10 0724008226','OK','2010-09-30 05:09:50',2),(82,'meb 15 0724008226','OK','2010-09-30 05:10:10',2),(83,'meb 21 0724008226','OK','2010-09-30 05:10:47',2),(84,'meb 21 0724008226','OK','2010-09-30 05:26:15',2),(85,'meb 25 0724008226','OK','2010-09-30 05:26:23',2),(86,'meb 27 0724008226','OK','2010-09-30 06:15:13',2),(87,'meb 27 0724008226','OK','2010-09-30 07:30:39',2),(88,'testing live','OK','2010-10-01 10:48:27',12),(89,'ABCD 151 0724008231','OK','2010-10-01 13:08:31',8),(90,'ABCD 151 0724008231','OK','2010-10-05 13:04:40',8),(92,'IVM 256 724008226','OK FLSMS','2010-10-25 11:00:51',2),(93,'ALB 256 724008226','OK FLSMS','2010-10-25 11:03:50',2),(94,'LOPP 256 724008226','OK FLSMS','2010-10-25 11:07:27',2),(95,'ABCD 256 724008226','OK FLSMS','2010-10-25 11:08:46',2),(3696,'IVM 50 +19193604046','FLSMS: OK','2010-11-25 16:40:01',28),(3697,'ALB 100 +19193604046','FLSMS: OK','2010-11-25 16:40:02',28),(3698,'ALB 100 +19193604046','FLSMS: OK','2010-11-25 16:40:03',28),(3699,'IVM 50 +19193604046','FLSMS: OK','2010-11-25 16:40:04',28),(3700,'IVM 65 +19193604046','FLSMS: OK','2010-11-25 16:40:05',28),(3701,'TEST 125 +19193604046','FLSMS: Treatment ID doesn\'t exist: TEST','2010-11-25 16:40:06',28),(3702,'ANOTHER TEST 999 +19193604046','FLSMS: Drug/Treatment ID incorrect.','2010-11-25 16:40:07',28),(3703,'TEST 125 +19193604046','FLSMS: Treatment ID doesn\'t exist: TEST','2010-11-25 16:40:08',28),(3704,'JJJJ 999 +19193604046','FLSMS: Treatment ID doesn\'t exist: JJJJ','2010-11-25 16:40:09',28),(3705,'YUKKU 552 +19193604046','FLSMS: Drug/Treatment ID incorrect.','2010-11-25 16:40:10',28),(3706,'TEST 100 +19193604046','FLSMS: Treatment ID doesn\'t exist: TEST','2010-11-25 16:40:11',28),(3707,'BOMI 800 +19193604046','FLSMS: Treatment ID doesn\'t exist: BOMI','2010-11-25 16:40:12',28),(3708,'ABCD 666 +19193604046','FLSMS: OK','2010-11-25 16:40:13',28),(3709,'ABCD 999 +19193604046','FLSMS: OK','2010-11-25 16:40:14',28),(3710,'MERC 15 +19193604046','FLSMS: Treatment ID doesn\'t exist: MERC','2010-11-25 16:40:15',28),(3711,'ALB 15 +19193604046','FLSMS: OK','2010-11-25 16:40:16',28),(3712,'ALB 15 +19193604046','FLSMS: OK','2010-11-25 16:40:17',28),(3713,'MSF 500 +19193604046','FLSMS: Drug ID doesn\'t exist: MSF','2010-11-25 16:40:18',28),(3714,'MAUD 522 +19193604046','FLSMS: Treatment ID doesn\'t exist: MAUD','2010-11-25 16:40:19',28),(3715,'JMHJ 100 +19193604046','FLSMS: Treatment ID doesn\'t exist: JMHJ','2010-11-25 16:40:20',28),(3716,'ALB 8888 +19193604046','FLSMS: OK','2010-11-25 16:40:21',28),(3717,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:22',28),(3718,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:23',28),(3719,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:24',28),(3720,'ALB 500 +19193604046','FLSMS: OK','2010-11-25 16:40:25',28),(3721,'ALB 500 +19193604046','FLSMS: OK','2010-11-25 16:40:26',28),(3722,'ALB 500 +19193604046','FLSMS: OK','2010-11-25 16:40:27',28),(3723,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:28',28),(3724,'ALB 500 +19193604046','FLSMS: OK','2010-11-25 16:40:29',28),(3725,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:30',28),(3726,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:31',28),(3727,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:32',22),(3728,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:33',22),(3729,'ALB 123 +27724008226','FLSMS: OK','2010-11-25 16:40:34',22),(3730,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:35',22),(3731,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:36',22),(3732,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:37',22),(3733,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:38',22),(3734,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:39',22),(3735,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:40',22),(3736,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:41',22),(3737,' 0 +27724008226','FLSMS: incomplete data: cannot import','2010-11-25 16:40:42',22),(3738,'IVM 25 +27724008226','FLSMS: OK','2010-11-25 16:40:43',22),(3739,'JMJ 589 +27724008226','FLSMS: Drug ID doesn\'t exist: JMJ','2010-11-25 16:40:44',22),(3740,'PMIX 1235 +27724008226','FLSMS: Treatment ID doesn\'t exist: PMIX','2010-11-25 16:40:45',22),(3741,'PIMX 1256 +27724008226','FLSMS: Treatment ID doesn\'t exist: PIMX','2010-11-25 16:40:46',22),(3742,'IVR 5000 +19193604046','FLSMS: Drug ID doesn\'t exist: IVR','2010-11-25 16:40:47',28),(3743,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:48',28),(3744,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:49',28),(3745,'ZMO 10000 +19193604046','FLSMS: Drug ID doesn\'t exist: ZMO','2010-11-25 16:40:50',28),(3746,'MEC 10000 +19193604046','FLSMS: Drug ID doesn\'t exist: MEC','2010-11-25 16:40:51',28),(3747,'PZQ 10000 +19193604046','FLSMS: OK','2010-11-25 16:40:52',28),(3748,'zmt 10000 +19193604046','FLSMS: Drug ID doesn\'t exist: zmt','2010-11-25 16:40:53',28),(3749,'IVR 10000 +19193604046','FLSMS: Drug ID doesn\'t exist: IVR','2010-11-25 16:40:54',28),(3750,'ALB 10000 +19193604046','FLSMS: OK','2010-11-25 16:40:55',28),(3751,'PIMX 5000 +19193604046','FLSMS: Treatment ID doesn\'t exist: PIMX','2010-11-25 16:40:56',28),(3752,'ZAXX 5000 +19193604046','FLSMS: Treatment ID doesn\'t exist: ZAXX','2010-11-25 16:40:57',28),(3753,' 0 +19193604046','FLSMS: incomplete data: cannot import','2010-11-25 16:40:58',28),(3754,'ZTT 123 +27724008226','FLSMS: OK','2010-11-25 16:40:59',22),(3755,'DERT 1112552 +27724008226','FLSMS: OK','2010-11-25 16:41:00',22),(3756,'MEB 123 +27724008226','FLSMS: OK','2010-11-25 16:41:01',22),(3757,'IVM 9999 +27724008226','FLSMS: OK','2010-11-25 16:41:02',22),(3758,'DERT 9999 +27724008226','FLSMS: OK','2010-11-25 16:41:03',22),(3759,'','Manual assignmnet','2011-06-02 01:49:04',NULL),(3760,'','Manual assignmnet','2011-06-02 01:51:41',NULL),(3761,'','Manual assignmnet','2011-06-02 01:54:14',NULL),(3762,'','Manual assignmnet','2011-06-02 02:00:24',NULL),(3763,'','Manual assignmnet','2011-06-02 02:00:58',NULL),(3764,'','Manual assignmnet','2011-06-02 02:01:38',NULL),(3765,'','Manual assignmnet','2011-06-02 02:02:26',NULL),(3766,'','Manual assignmnet','2011-06-02 02:03:34',NULL),(3767,'','Manual assignmnet','2011-06-02 02:04:39',NULL),(3768,'','Manual assignmnet','2011-06-02 02:09:14',NULL),(3769,'','Manual assignmnet','2011-06-02 02:11:25',NULL),(3770,'','Manual assignmnet','2011-06-02 02:12:11',NULL),(3771,'','Manual assignmnet','2011-06-02 02:21:35',NULL),(3772,'','','2011-06-02 14:21:36',NULL),(3773,'','Manual assignmnet','2011-06-02 02:27:37',NULL),(3774,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3775,'','Manual assignmnet','2011-06-02 02:31:09',NULL),(3776,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3777,'','Manual assignmnet','2011-06-02 02:41:55',NULL),(3778,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3779,'','Manual assignmnet','2011-06-02 02:44:56',NULL),(3780,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3781,'','Manual assignmnet','2011-06-02 02:45:41',NULL),(3782,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3783,'','Manual assignmnet','2011-06-02 02:46:44',NULL),(3784,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3785,'','Manual assignmnet','2011-06-02 02:47:44',NULL),(3786,'','Manual assignmnet','0000-00-00 00:00:00',NULL),(3787,'','Manual assignmnet','2011-06-02 02:49:20',NULL),(3788,'','Manual assignmnet','2011-06-02 14:49:20',NULL),(3789,'','Manual assignmnet','2011-06-02 14:50:43',NULL),(3790,'','Manual assignmnet','2011-06-02 14:51:31',NULL),(3791,'','Manual assignmnet','2011-06-02 14:53:46',NULL),(3792,'','Manual assignmnet','2011-06-02 15:32:30',NULL),(3793,'','Manual assignmnet','2011-06-03 09:26:48',NULL),(3794,'','Manual assignmnet','2011-06-03 09:59:19',NULL),(3795,'Manual assignmnet','Manual assignmnet','2011-06-03 10:00:15',NULL),(3796,'Manual assignmnet','Manual assignmnet','2011-06-03 10:00:30',NULL),(3797,'Manual assignmnet','Manual assignmnet','2011-06-03 10:38:18',NULL),(3798,'Manual assignmnet','Manual assignmnet','2011-06-03 10:48:50',NULL),(3799,'Manual assignmnet','Manual assignmnet','2011-06-03 12:12:21',NULL),(3800,'Manual assignmnet','Manual assignmnet','2011-06-03 12:12:36',NULL),(3801,'Manual assignmnet','Manual assignmnet','2011-06-03 12:13:52',NULL),(3802,'Manual assignmnet','Manual assignmnet','2011-06-03 12:14:46',NULL),(3803,'Manual assignmnet','Manual assignmnet','2011-06-03 12:54:34',NULL),(3804,'Manual assignmnet','Manual assignmnet','2011-06-03 13:16:18',NULL),(3805,'Manual assignmnet','Manual assignmnet','2011-06-03 13:16:59',NULL),(3806,'Manual assignmnet','Manual assignmnet','2011-06-03 13:22:23',NULL),(3807,'Manual assignmnet','Manual assignmnet','2011-06-03 13:24:50',NULL),(3808,'Manual assignmnet','Manual assignmnet','2011-06-03 13:34:51',NULL),(3809,'Manual assignmnet','Manual assignmnet','2011-06-03 13:35:58',NULL),(3810,'Manual assignmnet','Manual assignmnet','2011-06-03 13:37:20',NULL),(3811,'Manual assignmnet','Manual assignmnet','2011-06-03 13:53:55',NULL),(3812,'Manual assignmnet','Manual assignmnet','2011-06-03 13:54:06',NULL),(3813,'Manual assignmnet','Manual assignmnet','2011-06-03 14:12:06',NULL),(3814,'Manual assignmnet','Manual assignmnet','2011-06-03 14:12:46',NULL),(3815,'Manual assignmnet','Manual assignmnet','2011-06-03 14:13:01',NULL),(3816,'Manual assignmnet','Manual assignmnet','2011-06-03 14:13:24',NULL),(3817,'Manual assignmnet','Manual assignmnet','2011-06-03 14:14:04',NULL),(3818,'Manual assignmnet','Manual assignmnet','2011-06-03 14:14:37',NULL),(3819,'Manual assignmnet','Manual assignmnet','2011-06-03 14:14:52',NULL),(3820,'Manual assignmnet','Manual assignmnet','2011-06-03 14:15:04',NULL),(3821,'Manual assignmnet','Manual assignmnet','2011-06-03 14:50:47',NULL),(3822,'Manual assignmnet','Manual assignmnet','2011-06-03 15:04:05',NULL),(3823,'Manual assignmnet','Manual assignmnet','2011-06-03 15:21:03',NULL),(3824,'Manual assignmnet','Manual assignmnet','2011-06-03 15:22:00',NULL),(3826,'Manual assignmnet','Manual assignmnet','2011-06-03 15:27:05',NULL),(3827,'Manual assignmnet','Manual assignmnet','2011-06-03 15:47:41',NULL),(3828,'Manual assignmnet','Manual assignmnet','2011-06-03 15:51:08',NULL),(3829,'Manual assignmnet','Manual assignmnet','2011-06-03 15:56:31',NULL),(3831,'Manual assignmnet','Manual assignmnet','2011-06-03 15:58:21',NULL),(3836,'Manual assignmnet','Manual assignmnet','2011-06-03 16:22:05',NULL),(3837,'Manual assignmnet','Manual assignmnet','2011-06-03 16:27:00',NULL),(3838,'Manual assignmnet','Manual assignmnet','2011-06-03 16:27:19',NULL),(3840,'Manual assignmnet','Manual assignmnet','2011-06-03 16:30:35',NULL),(3841,'Manual assignmnet','Manual assignmnet','2011-06-03 16:30:52',NULL),(3842,'Manual assignmnet','Manual assignmnet','2011-06-03 16:31:09',NULL),(3843,'Manual assignmnet','Manual assignmnet','2011-06-03 16:31:26',NULL),(3844,'Manual assignmnet','Manual assignmnet','2011-06-03 16:31:38',NULL),(3845,'Manual assignmnet','Manual assignmnet','2011-06-03 16:31:58',NULL),(3846,'Manual assignmnet','Manual assignmnet','2011-06-03 16:33:45',NULL),(3847,'Manual assignmnet','Manual assignmnet','2011-06-03 16:34:00',NULL),(3848,'Manual assignmnet','Manual assignmnet','2011-06-03 16:34:15',NULL),(3850,'Manual assignmnet','Manual assignmnet','2011-06-03 16:35:14',NULL),(3851,'Manual assignmnet','Manual assignmnet','2011-06-03 16:35:31',NULL),(3852,'Manual assignmnet','Manual assignmnet','2011-06-03 16:35:45',NULL),(3853,'Manual assignmnet','Manual assignmnet','2011-06-03 16:35:59',NULL),(3854,'Manual assignmnet','Manual assignmnet','2011-06-06 10:28:33',NULL),(3855,'Manual assignmnet','Manual assignmnet','2011-06-06 10:38:35',NULL),(3856,'Manual assignmnet','Manual assignmnet','2011-06-06 10:51:52',NULL),(3857,'Manual assignmnet','Manual assignmnet','2011-06-06 10:52:49',NULL),(3858,'Manual assignmnet','Manual assignmnet','2011-06-06 11:21:16',NULL),(3859,'Manual assignmnet','Manual assignmnet','2011-06-06 11:24:57',NULL),(3860,'Manual assignmnet','Manual assignmnet','2011-06-06 11:25:52',NULL),(3861,'Manual assignmnet','Manual assignmnet','2011-06-06 11:29:47',NULL),(3862,'Manual assignmnet','Manual assignmnet','2011-06-06 15:35:50',NULL),(3863,'Manual assignmnet','Manual assignmnet','2011-06-22 09:00:35',NULL),(3864,'Manual assignmnet','Manual assignmnet','2011-06-22 09:06:04',NULL),(3865,'Manual assignmnet','Manual assignmnet','2011-06-22 09:49:02',NULL),(3866,'Manual assignmnet','Manual assignmnet','2011-06-28 16:14:52',NULL),(3867,'Manual assignmnet','Manual assignmnet','2011-06-29 07:40:50',NULL),(3868,'Manual assignmnet','Manual assignmnet','2011-06-29 08:25:23',NULL),(3869,'Manual assignmnet','Manual assignmnet','2011-06-29 08:25:43',NULL),(3870,'Manual assignmnet','Manual assignmnet','2011-06-29 08:25:57',NULL),(3871,'Manual assignmnet','Manual assignmnet','2011-06-29 08:26:09',NULL),(3872,'Manual assignmnet','Manual assignmnet','2011-06-29 08:35:31',NULL),(3873,'Manual assignmnet','Manual assignmnet','2011-06-29 08:36:50',NULL),(3874,'Manual assignmnet','Manual assignmnet','2011-06-29 10:17:39',NULL),(3875,'Manual assignmnet','Manual assignmnet','2011-06-29 10:21:06',NULL),(3876,'Manual assignmnet','Manual assignmnet','2011-06-29 10:22:40',NULL),(3878,'Manual assignmnet','Manual assignmnet','2011-06-29 10:38:46',NULL),(3879,'Manual assignmnet','Manual assignmnet','2011-06-29 10:38:58',NULL),(3880,'Manual assignmnet','Manual assignmnet','2011-06-29 10:39:27',NULL),(3881,'Manual assignmnet','Manual assignmnet','2011-06-29 10:39:46',NULL),(3882,'Manual assignmnet','Manual assignmnet','2011-06-29 10:39:59',NULL),(3883,'Manual assignmnet','Manual assignmnet','2011-06-29 10:40:16',NULL),(3884,'Manual assignmnet','Manual assignmnet','2011-06-29 10:40:48',NULL),(3885,'Manual assignmnet','Manual assignmnet','2011-06-29 10:42:41',NULL),(3886,'Manual assignmnet','Manual assignmnet','2011-06-29 11:51:15',NULL),(3887,'Manual assignmnet','Manual assignmnet','2011-06-29 11:52:01',NULL),(3888,'Manual assignmnet','Manual assignmnet','2011-06-29 11:53:19',NULL),(3889,'Manual assignmnet','Manual assignmnet','2011-06-29 11:53:32',NULL),(3890,'Manual assignmnet','Manual assignmnet','2011-06-29 11:54:11',NULL),(3891,'Manual assignmnet','Manual assignmnet','2011-06-29 11:54:24',NULL),(3892,'Manual assignmnet','Manual assignmnet','2011-06-29 11:55:13',NULL),(3893,'Manual assignmnet','Manual assignmnet','2011-06-29 11:55:23',NULL),(3894,'Manual assignmnet','Manual assignmnet','2011-06-29 12:20:33',NULL),(3895,'Manual assignmnet','Manual assignmnet','2011-06-29 12:23:17',NULL),(3896,'Manual assignmnet','Manual assignmnet','2011-06-29 12:42:13',NULL),(3897,'Manual assignmnet','Manual assignmnet','2011-06-29 12:42:29',NULL),(3898,'Manual assignmnet','Manual assignmnet','2011-06-29 12:43:16',NULL),(3899,'Manual assignmnet','Manual assignmnet','2011-06-29 12:53:46',NULL),(3900,'Manual assignmnet','Manual assignmnet','2011-06-29 13:01:06',NULL),(3901,'Manual assignmnet','Manual assignmnet','2011-06-29 13:01:21',NULL),(3902,'Manual assignmnet','Manual assignmnet','2011-06-29 14:05:40',NULL),(3903,'Manual assignmnet','Manual assignmnet','2011-06-29 14:06:15',NULL),(3904,'Manual assignmnet','Manual assignmnet','2011-06-29 14:06:27',NULL),(3905,'Manual assignmnet','Manual assignmnet','2011-06-29 14:06:40',NULL),(3906,'Manual assignmnet','Manual assignmnet','2011-06-29 14:21:35',NULL);
/*!40000 ALTER TABLE `rawreports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` int(11) NOT NULL auto_increment,
  `quantity` int(5) NOT NULL,
  `created` datetime NOT NULL,
  `drug_id` int(11) default '0',
  `treatment_id` int(11) default '0',
  `rawreport_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=690 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
INSERT INTO `stats` VALUES (36,78,'2010-08-31 11:20:06',NULL,1,46,6,3),(37,4,'2010-08-31 11:20:47',3,NULL,47,6,3),(38,125,'2010-08-31 11:21:08',1,NULL,48,6,3),(39,92,'2010-08-31 11:21:58',0,4,49,6,3),(40,12,'2010-08-31 11:23:05',1,NULL,50,5,1),(41,12,'2010-08-31 11:25:04',1,NULL,51,8,3),(42,85,'2010-08-31 11:25:39',NULL,2,52,8,3),(43,52,'2010-08-31 11:26:05',NULL,2,53,2,2),(44,63,'2010-08-31 12:24:03',NULL,1,58,2,2),(45,63,'2010-08-31 12:25:41',1,NULL,59,2,2),(46,47,'2010-08-31 12:26:25',1,NULL,60,2,2),(47,152,'2010-08-31 12:27:23',2,NULL,61,3,1),(48,151,'2010-08-31 12:33:06',2,NULL,62,3,1),(49,8,'2010-08-31 12:34:26',NULL,2,64,2,2),(50,18,'2010-08-31 12:35:00',NULL,2,65,3,1),(51,19,'2010-08-31 12:35:25',5,0,66,6,3),(52,29,'2010-08-31 12:35:34',NULL,2,67,5,1),(53,30,'2010-08-31 12:36:20',NULL,2,68,3,3),(54,74,'2010-08-31 12:36:52',6,NULL,69,6,3),(55,253,'2010-08-31 13:23:51',1,NULL,70,8,3),(56,25,'2010-08-31 13:24:03',0,2,71,5,2),(57,25,'2010-09-30 04:27:47',NULL,2,72,8,3),(58,151,'2010-09-30 04:29:48',0,2,73,8,3),(59,6,'2010-09-30 04:36:50',3,0,74,2,3),(60,6,'2010-09-30 04:48:37',3,0,75,2,3),(61,9,'2010-09-30 05:05:46',3,0,76,2,3),(62,9,'2010-09-30 05:06:16',3,0,77,2,3),(63,9,'2010-09-30 05:06:38',3,0,78,2,3),(64,9,'2010-09-30 05:07:59',3,0,79,2,3),(65,10,'2010-09-30 05:09:31',3,0,80,2,3),(66,10,'2010-09-30 05:09:50',3,0,81,2,3),(67,15,'2010-09-30 05:10:10',3,0,82,2,3),(68,21,'2010-09-30 05:10:47',3,0,83,2,3),(69,21,'2010-09-30 05:26:15',3,0,84,2,3),(70,25,'2010-09-30 05:26:23',3,0,85,2,3),(71,27,'2010-09-30 06:15:13',3,NULL,86,2,3),(72,27,'2010-09-30 07:30:39',2,NULL,87,2,3),(75,1234,'2010-10-01 10:48:53',2,0,88,12,5),(76,776,'2010-10-01 11:41:23',5,NULL,46,8,3),(77,151,'2010-10-01 13:08:31',0,2,89,8,3),(78,151,'2010-10-05 13:04:40',0,2,90,8,3),(79,256,'2010-10-25 11:00:51',1,0,92,2,3),(80,256,'2010-10-25 11:03:50',2,0,93,2,3),(81,2569,'2010-10-25 11:07:27',NULL,4,94,3,3),(82,256,'2010-10-25 11:08:46',0,2,95,2,3),(83,850,'2010-11-08 11:57:21',1,0,100,27,5),(84,2222,'2010-11-08 12:01:05',1,0,102,27,5),(85,2,'2010-11-08 12:13:35',1,0,103,27,5),(86,2,'2010-11-08 12:13:36',2,0,104,27,5),(666,50,'2010-11-25 16:40:01',1,0,3696,28,6),(667,100,'2010-11-25 16:40:02',2,0,3697,28,6),(668,100,'2010-11-25 16:40:03',2,0,3698,28,6),(669,50,'2010-11-25 16:40:04',1,0,3699,28,6),(670,65,'2010-11-25 16:40:05',1,0,3700,28,6),(671,666,'2010-11-25 16:40:13',0,19,3708,28,6),(672,999,'2010-11-25 16:40:14',0,19,3709,28,6),(673,15,'2010-11-25 16:40:16',2,0,3711,28,6),(674,15,'2010-11-25 16:40:17',2,0,3712,28,6),(675,8888,'2010-11-25 16:40:21',2,0,3716,28,6),(676,500,'2010-11-25 16:40:25',2,0,3720,28,6),(677,500,'2010-11-25 16:40:26',2,0,3721,28,6),(678,500,'2010-11-25 16:40:27',2,0,3722,28,6),(679,500,'2010-11-25 16:40:29',2,0,3724,28,6),(680,123,'2010-11-25 16:40:34',2,0,3729,22,5),(681,25,'2010-11-25 16:40:43',1,0,3738,22,5),(682,10000,'2010-11-25 16:40:52',4,0,3747,28,6),(683,10000,'2010-11-25 16:40:55',2,0,3750,28,6),(684,123,'2010-11-25 16:40:59',5,0,3754,22,5),(685,1112552,'2010-11-25 16:41:00',0,12,3755,22,5),(686,123,'2010-11-25 16:41:01',3,0,3756,22,5),(687,9999,'2010-11-25 16:41:02',1,0,3757,22,5),(688,9999,'2010-11-25 16:41:03',0,12,3758,22,5),(689,89,'2011-03-08 07:46:00',4,0,46,4,6);
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE `statuses` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(35) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statuses`
--

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;
INSERT INTO `statuses` VALUES (1,'Accept'),(2,'Deliver'),(3,'Expire'),(4,'Return');
/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracks`
--

DROP TABLE IF EXISTS `tracks`;
CREATE TABLE `tracks` (
  `id` int(11) NOT NULL auto_increment,
  `kit_id` int(11) default NULL,
  `location_id` int(11) default NULL,
  `patient_id` int(11) default NULL,
  `status_id` int(11) default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `rawreport_id` int(11) default NULL,
  `phone_id` int(11) default NULL,
  `parent_location_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracks`
--

LOCK TABLES `tracks` WRITE;
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;
INSERT INTO `tracks` VALUES (137,52,9,NULL,2,'2011-06-29 12:23:17','2011-06-29 12:41:38',3895,NULL,3),(138,52,9,NULL,1,'2011-06-29 12:42:13','2011-06-29 12:42:13',3896,NULL,3),(144,52,1,NULL,2,'2011-06-29 14:05:40','2011-06-29 14:05:40',3902,NULL,9),(145,52,1,NULL,1,'2011-06-29 14:06:15','2011-06-29 14:06:15',3903,NULL,9),(146,52,NULL,1,2,'2011-06-29 14:06:27','2011-06-29 14:06:27',3904,NULL,1),(147,52,9,1,4,'2011-06-29 14:06:40','2011-06-29 14:06:40',3905,NULL,NULL),(148,53,NULL,NULL,2,'2011-06-29 14:21:35','2011-06-29 14:21:35',3906,NULL,5);
/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatments`
--

DROP TABLE IF EXISTS `treatments`;
CREATE TABLE `treatments` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(4) NOT NULL,
  `units` int(2) NOT NULL,
  `description` varchar(256) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `treatments`
--

LOCK TABLES `treatments` WRITE;
/*!40000 ALTER TABLE `treatments` DISABLE KEYS */;
INSERT INTO `treatments` VALUES (1,'ABSC',5,NULL),(4,'LOPP',7,NULL),(10,'ZZZZ',2,NULL),(12,'DERT',51,NULL),(18,'DERT',8,NULL),(19,'ABCD',3,'');
/*!40000 ALTER TABLE `treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','8db57f058f3daac554698a07b8eaf8df1e1d319b',8,'2010-08-29 23:11:29','2010-08-30 14:49:14'),(2,'moderator','972b31ee3bed7b8b8f965bbc967dcc4a4d8b67b5',9,'2010-08-29 23:11:44','2010-09-04 04:34:47'),(4,'pablo','48b4ade90b8b5107fc82828163292d2a27c68176',8,'2010-08-30 18:02:15','2010-08-30 18:02:15'),(6,'pdestefanis','f8b41181b25c2f23b749e2bc01bf7179c29810a3',10,'2010-09-16 09:59:10','2010-09-16 09:59:10');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-29 13:23:10
