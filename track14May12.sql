-- MySQL dump 10.13  Distrib 5.5.20, for Win32 (x86)
--
-- Host: localhost    Database: track
-- ------------------------------------------------------
-- Server version	5.5.20-log

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

DROP TABLE IF EXISTS `acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3394 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acos`
--

LOCK TABLES `acos` WRITE;
/*!40000 ALTER TABLE `acos` DISABLE KEYS */;
INSERT INTO `acos` VALUES (2993,NULL,NULL,NULL,'controllers',1,768),(3208,2993,NULL,NULL,'App',430,447),(3209,3208,NULL,NULL,'findLocationChildren',431,432),(3210,3208,NULL,NULL,'findLocationParent',433,434),(3211,3208,NULL,NULL,'findTopParent',435,436),(3212,3208,NULL,NULL,'findLevel',437,438),(3213,3208,NULL,NULL,'findLocationFirstChildren',439,440),(3214,3208,NULL,NULL,'getReport',441,442),(3215,3208,NULL,NULL,'sumChildren',443,444),(3216,3208,NULL,NULL,'processItems',445,446),(3217,2993,NULL,NULL,'Pages',448,455),(3218,3217,NULL,NULL,'display',449,450),(3219,3217,NULL,NULL,'updateJSONFile',451,452),(3220,3217,NULL,NULL,'getReports',453,454),(3221,2993,NULL,NULL,'Alerts',456,471),(3222,3221,NULL,NULL,'index',457,458),(3223,3221,NULL,NULL,'view',459,460),(3224,3221,NULL,NULL,'add',461,462),(3225,3221,NULL,NULL,'edit',463,464),(3226,3221,NULL,NULL,'delete',465,466),(3227,3221,NULL,NULL,'triggered',467,468),(3228,2993,NULL,NULL,'Approvals',472,483),(3229,3228,NULL,NULL,'index',473,474),(3230,3228,NULL,NULL,'view',475,476),(3231,3228,NULL,NULL,'add',477,478),(3232,3228,NULL,NULL,'edit',479,480),(3233,3228,NULL,NULL,'delete',481,482),(3234,2993,NULL,NULL,'ApprovalsStats',484,495),(3235,3234,NULL,NULL,'index',485,486),(3236,3234,NULL,NULL,'view',487,488),(3237,3234,NULL,NULL,'add',489,490),(3238,3234,NULL,NULL,'edit',491,492),(3239,3234,NULL,NULL,'delete',493,494),(3240,2993,NULL,NULL,'Items',496,507),(3241,3240,NULL,NULL,'index',497,498),(3242,3240,NULL,NULL,'view',499,500),(3243,3240,NULL,NULL,'add',501,502),(3244,3240,NULL,NULL,'edit',503,504),(3245,3240,NULL,NULL,'delete',505,506),(3246,2993,NULL,NULL,'Locations',508,519),(3247,3246,NULL,NULL,'index',509,510),(3248,3246,NULL,NULL,'view',511,512),(3249,3246,NULL,NULL,'add',513,514),(3250,3246,NULL,NULL,'edit',515,516),(3251,3246,NULL,NULL,'delete',517,518),(3252,2993,NULL,NULL,'Messagereceiveds',520,531),(3253,3252,NULL,NULL,'index',521,522),(3254,3252,NULL,NULL,'view',523,524),(3255,3252,NULL,NULL,'add',525,526),(3256,3252,NULL,NULL,'edit',527,528),(3257,3252,NULL,NULL,'delete',529,530),(3258,2993,NULL,NULL,'Modifiers',532,543),(3259,3258,NULL,NULL,'index',533,534),(3260,3258,NULL,NULL,'view',535,536),(3261,3258,NULL,NULL,'add',537,538),(3262,3258,NULL,NULL,'edit',539,540),(3263,3258,NULL,NULL,'delete',541,542),(3264,2993,NULL,NULL,'Phones',544,555),(3265,3264,NULL,NULL,'index',545,546),(3266,3264,NULL,NULL,'view',547,548),(3267,3264,NULL,NULL,'add',549,550),(3268,3264,NULL,NULL,'edit',551,552),(3269,3264,NULL,NULL,'delete',553,554),(3270,2993,NULL,NULL,'Rawreports',556,567),(3271,3270,NULL,NULL,'index',557,558),(3272,3270,NULL,NULL,'view',559,560),(3273,3270,NULL,NULL,'add',561,562),(3274,3270,NULL,NULL,'edit',563,564),(3275,3270,NULL,NULL,'delete',565,566),(3276,2993,NULL,NULL,'Roles',568,589),(3277,3276,NULL,NULL,'index',569,570),(3278,3276,NULL,NULL,'view',571,572),(3279,3276,NULL,NULL,'add',573,574),(3280,3276,NULL,NULL,'edit',575,576),(3281,3276,NULL,NULL,'delete',577,578),(3282,3276,NULL,NULL,'acl',579,580),(3283,3276,NULL,NULL,'adjustperm',581,582),(3284,3276,NULL,NULL,'cleanupAcl',583,584),(3285,2993,NULL,NULL,'Stats',590,635),(3286,3285,NULL,NULL,'index',591,592),(3287,3285,NULL,NULL,'view',593,594),(3288,3285,NULL,NULL,'add',595,596),(3289,3285,NULL,NULL,'edit',597,598),(3290,3285,NULL,NULL,'delete',599,600),(3291,3285,NULL,NULL,'update_select',601,602),(3292,3285,NULL,NULL,'sitems',603,604),(3293,3285,NULL,NULL,'ichart',605,606),(3294,3285,NULL,NULL,'facility',607,608),(3295,3285,NULL,NULL,'options',609,610),(3296,2993,NULL,NULL,'Users',636,659),(3297,3296,NULL,NULL,'login',637,638),(3298,3296,NULL,NULL,'logout',639,640),(3299,3296,NULL,NULL,'index',641,642),(3300,3296,NULL,NULL,'view',643,644),(3301,3296,NULL,NULL,'add',645,646),(3302,3296,NULL,NULL,'edit',647,648),(3303,3296,NULL,NULL,'delete',649,650),(3304,3296,NULL,NULL,'initDB',651,652),(3305,3296,NULL,NULL,'build_acl',653,654),(3306,3296,NULL,NULL,'changePass',655,656),(3307,3296,NULL,NULL,'resetUsers',657,658),(3308,2993,NULL,NULL,'Views',660,661),(3309,2993,NULL,NULL,'Messagesents',662,673),(3310,3309,NULL,NULL,'index',663,664),(3311,3309,NULL,NULL,'view',665,666),(3312,3309,NULL,NULL,'add',667,668),(3313,3309,NULL,NULL,'edit',669,670),(3314,3309,NULL,NULL,'delete',671,672),(3315,3276,NULL,NULL,'managePermissions',585,586),(3316,3276,NULL,NULL,'allowDenyPermission',587,588),(3317,3285,NULL,NULL,'aggregatedInventory',611,612),(3318,3285,NULL,NULL,'aggregatedChart',613,614),(3319,3285,NULL,NULL,'facilityInventory',615,616),(3320,3221,NULL,NULL,'triggeredAlerts',469,470),(3321,3285,NULL,NULL,'update_facility_select',617,618),(3322,3285,NULL,NULL,'graphTimeline',619,620),(3323,2993,NULL,NULL,'Statuses',674,685),(3325,3323,NULL,NULL,'index',675,676),(3327,3323,NULL,NULL,'view',677,678),(3329,3323,NULL,NULL,'add',679,680),(3331,3323,NULL,NULL,'edit',681,682),(3333,3323,NULL,NULL,'delete',683,684),(3335,2993,NULL,NULL,'Patients',686,697),(3337,3335,NULL,NULL,'index',687,688),(3339,3335,NULL,NULL,'view',689,690),(3341,3335,NULL,NULL,'add',691,692),(3343,3335,NULL,NULL,'edit',693,694),(3345,3335,NULL,NULL,'delete',695,696),(3347,3285,NULL,NULL,'update_patient_select',621,622),(3349,3285,NULL,NULL,'update_sent_to_select',623,624),(3351,3285,NULL,NULL,'patientsWithKits',625,626),(3353,3285,NULL,NULL,'mismatchedDeliveries',627,628),(3355,3285,NULL,NULL,'kitsExpired',629,630),(3357,3285,NULL,NULL,'kitsInTransit',631,632),(3358,3285,NULL,NULL,'store',633,634),(3359,2993,NULL,NULL,'Api',698,701),(3360,3359,NULL,NULL,'getPhone',699,700),(3361,2993,NULL,NULL,'Apis',702,719),(3362,3361,NULL,NULL,'getPhone',703,704),(3363,3361,NULL,NULL,'setReport',705,706),(3364,3361,NULL,NULL,'discardUnit',707,708),(3365,3361,NULL,NULL,'receiveUnit',709,710),(3366,3361,NULL,NULL,'assignToPatient',711,712),(3367,3361,NULL,NULL,'assignToFacility',713,714),(3368,2993,NULL,NULL,'Batches',720,731),(3369,3368,NULL,NULL,'index',721,722),(3370,3368,NULL,NULL,'view',723,724),(3371,3368,NULL,NULL,'add',725,726),(3372,3368,NULL,NULL,'edit',727,728),(3373,3368,NULL,NULL,'delete',729,730),(3374,2993,NULL,NULL,'Units',732,743),(3375,3374,NULL,NULL,'index',733,734),(3376,3374,NULL,NULL,'view',735,736),(3377,3374,NULL,NULL,'add',737,738),(3378,3374,NULL,NULL,'edit',739,740),(3379,3374,NULL,NULL,'delete',741,742),(3380,2993,NULL,NULL,'UnitsItems',744,755),(3381,3380,NULL,NULL,'index',745,746),(3382,3380,NULL,NULL,'view',747,748),(3383,3380,NULL,NULL,'add',749,750),(3384,3380,NULL,NULL,'edit',751,752),(3385,3380,NULL,NULL,'delete',753,754),(3386,2993,NULL,NULL,'UnitsBatches',756,767),(3387,3386,NULL,NULL,'index',757,758),(3388,3386,NULL,NULL,'view',759,760),(3389,3386,NULL,NULL,'add',761,762),(3390,3386,NULL,NULL,'edit',763,764),(3391,3386,NULL,NULL,'delete',765,766),(3392,3361,NULL,NULL,'findPatient',715,716),(3393,3361,NULL,NULL,'findUnit',717,718);
/*!40000 ALTER TABLE `acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alerts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `location_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `threshold` int(11) DEFAULT NULL,
  `conditions` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerts`
--

LOCK TABLES `alerts` WRITE;
/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;
INSERT INTO `alerts` VALUES (31,20,14,20001,2,2,'2011-08-26 13:58:26'),(33,20,16,15,3,2,'2011-08-26 14:00:24'),(34,20,18,150,1,1,'2011-08-26 17:45:31'),(35,22,22,3670,3,2,'2011-08-27 10:50:18'),(36,5,1,1500,2,1,'2011-08-27 10:57:10'),(37,30,22,25,1,2,'2011-09-02 14:41:29');
/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approvals`
--

DROP TABLE IF EXISTS `approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `approvals` (
  `id` int(11) NOT NULL DEFAULT '0',
  `messagereceived_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvals`
--

LOCK TABLES `approvals` WRITE;
/*!40000 ALTER TABLE `approvals` DISABLE KEYS */;
INSERT INTO `approvals` VALUES (32,2331,1,'2011-09-01 15:05:03'),(33,2337,1,'2011-09-01 17:04:50'),(34,2426,6,'2011-09-09 15:59:14'),(35,2428,6,'2011-09-09 16:01:50'),(36,2467,6,'2011-09-09 17:02:02');
/*!40000 ALTER TABLE `approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approvals_stats`
--

DROP TABLE IF EXISTS `approvals_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `approvals_stats` (
  `id` int(11) NOT NULL DEFAULT '0',
  `approval_id` int(11) DEFAULT NULL,
  `stat_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvals_stats`
--

LOCK TABLES `approvals_stats` WRITE;
/*!40000 ALTER TABLE `approvals_stats` DISABLE KEYS */;
INSERT INTO `approvals_stats` VALUES (288,30,937),(289,30,760),(290,30,984),(291,30,924),(292,30,898),(293,30,800),(294,31,950),(295,31,768),(296,31,981),(297,31,947),(298,31,384),(299,31,402),(300,31,760),(301,31,937),(302,31,984),(303,31,898),(304,31,924),(305,31,800),(306,31,960),(307,31,766),(308,31,764),(309,31,961),(310,31,350),(311,31,770),(312,31,949),(313,31,902),(314,31,922),(315,31,896),(316,31,802),(317,32,950),(318,32,768),(319,32,981),(320,33,950),(321,33,768),(322,33,981),(323,34,884),(324,34,266),(325,34,1019),(326,36,1011),(327,36,1005),(328,36,330),(329,36,378),(330,36,278),(331,36,884),(332,36,266),(333,36,1024),(334,36,392),(335,36,1023),(336,36,288),(337,36,993);
/*!40000 ALTER TABLE `approvals_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aros`
--

LOCK TABLES `aros` WRITE;
/*!40000 ALTER TABLE `aros` DISABLE KEYS */;
INSERT INTO `aros` VALUES (1,NULL,'Role',1,'Admin',1,4),(2,NULL,'Role',2,'Manager',5,14),(3,1,'User',1,'',2,3),(4,NULL,'Role',3,'User',15,24),(5,2,'User',2,'',6,7),(6,4,'User',3,'',16,17),(7,2,'User',6,'',8,9),(8,4,'User',4,'',18,19),(9,4,'User',5,'',20,21),(10,4,'User',7,'',22,23),(13,2,'User',9,'',10,11),(14,2,'User',10,'',12,13);
/*!40000 ALTER TABLE `aros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aros_acos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=884 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aros_acos`
--

LOCK TABLES `aros_acos` WRITE;
/*!40000 ALTER TABLE `aros_acos` DISABLE KEYS */;
INSERT INTO `aros_acos` VALUES (1,1,3218,'0','0','0','0'),(3,2,3218,'0','0','0','0'),(5,4,3218,'0','0','0','0'),(7,1,3222,'0','0','0','0'),(9,2,3222,'0','0','0','0'),(11,4,3222,'0','0','0','0'),(13,1,3223,'0','0','0','0'),(15,2,3223,'0','0','0','0'),(17,4,3223,'0','0','0','0'),(19,1,3224,'0','0','0','0'),(21,2,3224,'0','0','0','0'),(23,4,3224,'0','0','0','0'),(25,1,3225,'0','0','0','0'),(27,2,3225,'0','0','0','0'),(29,4,3225,'0','0','0','0'),(31,1,3226,'0','0','0','0'),(33,2,3226,'0','0','0','0'),(35,4,3226,'0','0','0','0'),(37,1,3320,'0','0','0','0'),(39,2,3320,'0','0','0','0'),(41,4,3320,'0','0','0','0'),(43,1,3229,'0','0','0','0'),(45,2,3229,'0','0','0','0'),(47,4,3229,'0','0','0','0'),(49,1,3230,'0','0','0','0'),(51,2,3230,'0','0','0','0'),(53,4,3230,'0','0','0','0'),(55,1,3235,'0','0','0','0'),(57,2,3235,'0','0','0','0'),(59,4,3235,'0','0','0','0'),(61,1,3236,'0','0','0','0'),(63,2,3236,'0','0','0','0'),(65,4,3236,'0','0','0','0'),(67,1,3241,'0','0','0','0'),(69,2,3241,'0','0','0','0'),(71,4,3241,'0','0','0','0'),(73,1,3242,'0','0','0','0'),(75,2,3242,'0','0','0','0'),(77,4,3242,'0','0','0','0'),(79,1,3243,'0','0','0','0'),(81,2,3243,'0','0','0','0'),(83,4,3243,'0','0','0','0'),(85,1,3244,'0','0','0','0'),(87,2,3244,'0','0','0','0'),(89,4,3244,'0','0','0','0'),(91,1,3245,'0','0','0','0'),(93,2,3245,'0','0','0','0'),(95,4,3245,'0','0','0','0'),(97,1,3247,'0','0','0','0'),(99,2,3247,'0','0','0','0'),(101,4,3247,'0','0','0','0'),(103,1,3248,'0','0','0','0'),(105,2,3248,'0','0','0','0'),(107,4,3248,'0','0','0','0'),(109,1,3249,'0','0','0','0'),(111,2,3249,'0','0','0','0'),(113,4,3249,'0','0','0','0'),(115,1,3250,'0','0','0','0'),(117,2,3250,'0','0','0','0'),(119,4,3250,'0','0','0','0'),(121,1,3251,'0','0','0','0'),(123,2,3251,'0','0','0','0'),(125,4,3251,'0','0','0','0'),(127,1,3253,'0','0','0','0'),(129,2,3253,'0','0','0','0'),(131,4,3253,'0','0','0','0'),(133,1,3254,'0','0','0','0'),(135,2,3254,'0','0','0','0'),(137,4,3254,'0','0','0','0'),(139,1,3310,'0','0','0','0'),(141,2,3310,'0','0','0','0'),(143,4,3310,'0','0','0','0'),(145,1,3311,'0','0','0','0'),(147,2,3311,'0','0','0','0'),(149,4,3311,'0','0','0','0'),(151,1,3259,'0','0','0','0'),(153,2,3259,'0','0','0','0'),(155,4,3259,'0','0','0','0'),(157,1,3260,'0','0','0','0'),(159,2,3260,'0','0','0','0'),(161,4,3260,'0','0','0','0'),(163,1,3261,'0','0','0','0'),(165,2,3261,'0','0','0','0'),(167,4,3261,'0','0','0','0'),(169,1,3262,'0','0','0','0'),(171,2,3262,'0','0','0','0'),(173,4,3262,'0','0','0','0'),(175,1,3263,'0','0','0','0'),(177,2,3263,'0','0','0','0'),(179,4,3263,'0','0','0','0'),(181,1,3265,'0','0','0','0'),(183,2,3265,'0','0','0','0'),(185,4,3265,'0','0','0','0'),(187,1,3266,'0','0','0','0'),(189,2,3266,'0','0','0','0'),(191,4,3266,'0','0','0','0'),(193,1,3267,'0','0','0','0'),(195,2,3267,'0','0','0','0'),(197,4,3267,'0','0','0','0'),(199,1,3268,'0','0','0','0'),(201,2,3268,'0','0','0','0'),(203,4,3268,'0','0','0','0'),(205,1,3269,'0','0','0','0'),(207,2,3269,'0','0','0','0'),(209,4,3269,'0','0','0','0'),(211,1,3277,'0','0','0','0'),(213,2,3277,'0','0','0','0'),(215,4,3277,'0','0','0','0'),(217,1,3278,'0','0','0','0'),(219,2,3278,'0','0','0','0'),(221,4,3278,'0','0','0','0'),(223,1,3279,'0','0','0','0'),(225,2,3279,'0','0','0','0'),(227,4,3279,'0','0','0','0'),(229,1,3280,'0','0','0','0'),(231,2,3280,'0','0','0','0'),(233,4,3280,'0','0','0','0'),(235,1,3281,'0','0','0','0'),(237,2,3281,'0','0','0','0'),(239,4,3281,'0','0','0','0'),(241,1,3315,'0','0','0','0'),(243,2,3315,'0','0','0','0'),(245,4,3315,'0','0','0','0'),(247,1,3316,'0','0','0','0'),(249,2,3316,'0','0','0','0'),(251,4,3316,'0','0','0','0'),(253,1,3286,'0','0','0','0'),(255,2,3286,'0','0','0','0'),(257,4,3286,'0','0','0','0'),(259,1,3287,'0','0','0','0'),(261,2,3287,'0','0','0','0'),(263,4,3287,'0','0','0','0'),(265,1,3288,'0','0','0','0'),(267,2,3288,'0','0','0','0'),(269,4,3288,'0','0','0','0'),(271,1,3289,'0','0','0','0'),(273,2,3289,'0','0','0','0'),(275,4,3289,'0','0','0','0'),(277,1,3290,'0','0','0','0'),(279,2,3290,'0','0','0','0'),(281,4,3290,'0','0','0','0'),(283,1,3321,'0','0','0','0'),(285,2,3321,'0','0','0','0'),(287,4,3321,'0','0','0','0'),(289,1,3317,'0','0','0','0'),(291,2,3317,'0','0','0','0'),(293,4,3317,'0','0','0','0'),(295,1,3318,'0','0','0','0'),(297,2,3318,'0','0','0','0'),(299,4,3318,'0','0','0','0'),(301,1,3319,'0','0','0','0'),(303,2,3319,'0','0','0','0'),(305,4,3319,'0','0','0','0'),(307,1,3322,'0','0','0','0'),(309,2,3322,'0','0','0','0'),(311,4,3322,'0','0','0','0'),(313,1,3295,'0','0','0','0'),(315,2,3295,'0','0','0','0'),(317,4,3295,'0','0','0','0'),(319,1,3297,'0','0','0','0'),(321,2,3297,'0','0','0','0'),(323,4,3297,'0','0','0','0'),(325,1,3298,'0','0','0','0'),(327,2,3298,'0','0','0','0'),(329,4,3298,'0','0','0','0'),(331,1,3299,'0','0','0','0'),(333,2,3299,'0','0','0','0'),(335,4,3299,'0','0','0','0'),(337,1,3300,'0','0','0','0'),(339,2,3300,'0','0','0','0'),(341,4,3300,'0','0','0','0'),(343,1,3301,'0','0','0','0'),(345,2,3301,'0','0','0','0'),(347,4,3301,'0','0','0','0'),(349,1,3302,'0','0','0','0'),(351,2,3302,'0','0','0','0'),(353,4,3302,'0','0','0','0'),(355,1,3303,'0','0','0','0'),(357,2,3303,'0','0','0','0'),(359,4,3303,'0','0','0','0'),(361,1,3306,'0','0','0','0'),(363,2,3306,'0','0','0','0'),(365,4,3306,'0','0','0','0'),(367,1,3218,'1','1','1','1'),(369,1,3222,'1','1','1','1'),(371,1,3223,'1','1','1','1'),(373,1,3224,'1','1','1','1'),(375,1,3225,'1','1','1','1'),(377,1,3226,'1','1','1','1'),(379,1,3320,'1','1','1','1'),(381,1,3229,'1','1','1','1'),(383,1,3230,'1','1','1','1'),(385,1,3235,'1','1','1','1'),(387,1,3236,'1','1','1','1'),(389,1,3241,'1','1','1','1'),(391,1,3242,'1','1','1','1'),(393,1,3243,'1','1','1','1'),(395,1,3244,'1','1','1','1'),(397,1,3245,'1','1','1','1'),(399,1,3247,'1','1','1','1'),(401,1,3248,'1','1','1','1'),(403,1,3249,'1','1','1','1'),(405,1,3250,'1','1','1','1'),(407,1,3251,'1','1','1','1'),(409,1,3253,'1','1','1','1'),(411,1,3254,'1','1','1','1'),(413,1,3310,'1','1','1','1'),(415,1,3311,'1','1','1','1'),(417,1,3259,'1','1','1','1'),(419,1,3260,'1','1','1','1'),(421,1,3261,'1','1','1','1'),(423,1,3262,'1','1','1','1'),(425,1,3263,'1','1','1','1'),(427,1,3265,'1','1','1','1'),(429,1,3266,'1','1','1','1'),(431,1,3267,'1','1','1','1'),(433,1,3268,'1','1','1','1'),(435,1,3269,'1','1','1','1'),(437,1,3277,'1','1','1','1'),(439,1,3278,'1','1','1','1'),(441,1,3279,'1','1','1','1'),(443,1,3280,'1','1','1','1'),(445,1,3281,'1','1','1','1'),(447,1,3315,'1','1','1','1'),(449,1,3316,'1','1','1','1'),(451,1,3286,'1','1','1','1'),(453,1,3287,'1','1','1','1'),(455,1,3288,'1','1','1','1'),(457,1,3289,'1','1','1','1'),(459,1,3290,'1','1','1','1'),(461,1,3321,'1','1','1','1'),(463,1,3317,'1','1','1','1'),(465,1,3318,'1','1','1','1'),(467,1,3319,'1','1','1','1'),(469,1,3322,'1','1','1','1'),(471,1,3295,'1','1','1','1'),(473,1,3297,'1','1','1','1'),(475,1,3298,'1','1','1','1'),(477,1,3299,'1','1','1','1'),(479,1,3300,'1','1','1','1'),(481,1,3301,'1','1','1','1'),(483,1,3302,'1','1','1','1'),(485,1,3303,'1','1','1','1'),(487,1,3306,'1','1','1','1'),(489,2,3218,'1','1','1','1'),(491,4,3218,'1','1','1','1'),(493,2,3286,'1','1','1','1'),(495,2,3287,'1','1','1','1'),(497,2,3288,'1','1','1','1'),(499,2,3289,'1','1','1','1'),(501,2,3290,'1','1','1','1'),(503,2,3321,'1','1','1','1'),(505,2,3317,'1','1','1','1'),(507,2,3318,'1','1','1','1'),(509,2,3319,'1','1','1','1'),(511,2,3322,'1','1','1','1'),(513,2,3295,'1','1','1','1'),(515,2,3316,'1','1','1','1'),(517,2,3315,'1','1','1','1'),(519,2,3277,'1','1','1','1'),(521,2,3297,'1','1','1','1'),(523,4,3297,'1','1','1','1'),(525,2,3298,'1','1','1','1'),(527,4,3306,'1','1','1','1'),(529,2,3306,'1','1','1','1'),(531,2,3299,'1','1','1','1'),(533,2,3300,'1','1','1','1'),(535,2,3301,'1','1','1','1'),(537,2,3302,'1','1','1','1'),(539,4,3241,'1','1','1','1'),(541,2,3241,'1','1','1','1'),(543,4,3242,'1','1','1','1'),(545,2,3242,'1','1','1','1'),(547,4,3241,'0','0','0','0'),(549,2,3247,'1','1','1','1'),(551,2,3249,'1','1','1','1'),(553,4,3248,'1','1','1','1'),(555,2,3248,'1','1','1','1'),(557,2,3250,'1','1','1','1'),(559,1,3286,'0','0','0','0'),(561,1,3287,'0','0','0','0'),(563,1,3288,'0','0','0','0'),(565,1,3289,'0','0','0','0'),(567,1,3290,'0','0','0','0'),(569,1,3321,'0','0','0','0'),(571,1,3317,'0','0','0','0'),(573,1,3318,'0','0','0','0'),(575,1,3319,'0','0','0','0'),(577,1,3322,'0','0','0','0'),(579,1,3295,'0','0','0','0'),(581,1,3286,'1','1','1','1'),(583,1,3287,'1','1','1','1'),(585,1,3288,'1','1','1','1'),(587,1,3289,'1','1','1','1'),(589,1,3290,'1','1','1','1'),(591,1,3321,'1','1','1','1'),(593,1,3317,'1','1','1','1'),(595,1,3318,'1','1','1','1'),(597,1,3319,'1','1','1','1'),(599,1,3322,'1','1','1','1'),(601,1,3295,'1','1','1','1'),(603,4,3318,'1','1','1','1'),(605,4,3322,'1','1','1','1'),(607,4,3321,'1','1','1','1'),(609,4,3319,'1','1','1','1'),(611,4,3317,'1','1','1','1'),(613,4,3298,'1','1','1','1'),(615,1,3297,'0','0','0','0'),(617,1,3297,'1','1','1','1'),(619,1,3298,'0','0','0','0'),(621,1,3298,'1','1','1','1'),(623,1,3297,'0','0','0','0'),(625,1,3298,'0','0','0','0'),(627,1,3299,'0','0','0','0'),(629,1,3300,'0','0','0','0'),(631,1,3301,'0','0','0','0'),(633,1,3302,'0','0','0','0'),(635,1,3303,'0','0','0','0'),(637,1,3306,'0','0','0','0'),(639,1,3297,'1','1','1','1'),(641,1,3298,'1','1','1','1'),(643,1,3299,'1','1','1','1'),(645,1,3300,'1','1','1','1'),(647,1,3301,'1','1','1','1'),(649,1,3302,'1','1','1','1'),(651,1,3303,'1','1','1','1'),(653,1,3306,'1','1','1','1'),(655,1,3286,'0','0','0','0'),(657,1,3287,'0','0','0','0'),(659,1,3288,'0','0','0','0'),(661,1,3289,'0','0','0','0'),(663,1,3290,'0','0','0','0'),(665,1,3321,'0','0','0','0'),(667,1,3317,'0','0','0','0'),(669,1,3318,'0','0','0','0'),(671,1,3319,'0','0','0','0'),(673,1,3322,'0','0','0','0'),(675,1,3295,'0','0','0','0'),(677,1,3286,'1','1','1','1'),(679,1,3287,'1','1','1','1'),(681,1,3288,'1','1','1','1'),(683,1,3289,'1','1','1','1'),(685,1,3290,'1','1','1','1'),(687,1,3321,'1','1','1','1'),(689,1,3317,'1','1','1','1'),(691,1,3318,'1','1','1','1'),(693,1,3319,'1','1','1','1'),(695,1,3322,'1','1','1','1'),(697,1,3295,'1','1','1','1'),(699,1,3325,'0','0','0','0'),(701,2,3325,'0','0','0','0'),(703,4,3325,'0','0','0','0'),(705,1,3327,'0','0','0','0'),(707,2,3327,'0','0','0','0'),(709,4,3327,'0','0','0','0'),(711,1,3329,'0','0','0','0'),(713,2,3329,'0','0','0','0'),(715,4,3329,'0','0','0','0'),(717,1,3331,'0','0','0','0'),(719,2,3331,'0','0','0','0'),(721,4,3331,'0','0','0','0'),(723,1,3333,'0','0','0','0'),(725,2,3333,'0','0','0','0'),(727,4,3333,'0','0','0','0'),(729,1,3337,'1','1','1','1'),(731,2,3337,'1','1','1','1'),(733,4,3337,'0','0','0','0'),(735,1,3339,'1','1','1','1'),(737,2,3339,'1','1','1','1'),(739,4,3339,'1','1','1','1'),(741,1,3341,'1','1','1','1'),(743,2,3341,'1','1','1','1'),(745,4,3341,'0','0','0','0'),(747,1,3343,'1','1','1','1'),(749,2,3343,'1','1','1','1'),(751,4,3343,'0','0','0','0'),(753,1,3345,'1','1','1','1'),(755,2,3345,'1','1','1','1'),(757,4,3345,'0','0','0','0'),(759,1,3347,'1','1','1','1'),(761,2,3347,'1','1','1','1'),(763,4,3347,'1','1','1','1'),(765,1,3349,'1','1','1','1'),(767,2,3349,'1','1','1','1'),(769,4,3349,'1','1','1','1'),(771,1,3351,'1','1','1','1'),(773,2,3351,'1','1','1','1'),(775,4,3351,'1','1','1','1'),(777,1,3353,'1','1','1','1'),(779,2,3353,'1','1','1','1'),(781,4,3353,'1','1','1','1'),(783,1,3355,'1','1','1','1'),(785,2,3355,'1','1','1','1'),(787,4,3355,'1','1','1','1'),(789,1,3357,'1','1','1','1'),(791,2,3357,'1','1','1','1'),(793,4,3357,'1','1','1','1'),(794,1,3358,'1','1','1','1'),(795,2,3358,'0','0','0','0'),(796,4,3358,'0','0','0','0'),(797,1,3360,'1','1','1','1'),(798,2,3360,'0','0','0','0'),(799,4,3360,'0','0','0','0'),(800,1,3362,'1','1','1','1'),(801,2,3362,'0','0','0','0'),(802,4,3362,'0','0','0','0'),(803,1,3363,'1','1','1','1'),(804,2,3363,'0','0','0','0'),(805,4,3363,'0','0','0','0'),(806,1,3364,'1','1','1','1'),(807,2,3364,'0','0','0','0'),(808,4,3364,'0','0','0','0'),(809,1,3365,'1','1','1','1'),(810,2,3365,'0','0','0','0'),(811,4,3365,'0','0','0','0'),(812,1,3366,'1','1','1','1'),(813,2,3366,'0','0','0','0'),(814,4,3366,'0','0','0','0'),(815,1,3367,'1','1','1','1'),(816,2,3367,'0','0','0','0'),(817,4,3367,'0','0','0','0'),(818,1,3369,'1','1','1','1'),(819,2,3369,'0','0','0','0'),(820,4,3369,'0','0','0','0'),(821,1,3370,'1','1','1','1'),(822,2,3370,'0','0','0','0'),(823,4,3370,'0','0','0','0'),(824,1,3371,'1','1','1','1'),(825,2,3371,'0','0','0','0'),(826,4,3371,'0','0','0','0'),(827,1,3372,'1','1','1','1'),(828,2,3372,'0','0','0','0'),(829,4,3372,'0','0','0','0'),(830,1,3373,'1','1','1','1'),(831,2,3373,'0','0','0','0'),(832,4,3373,'0','0','0','0'),(833,1,3375,'1','1','1','1'),(834,2,3375,'0','0','0','0'),(835,4,3375,'0','0','0','0'),(836,1,3376,'1','1','1','1'),(837,2,3376,'0','0','0','0'),(838,4,3376,'0','0','0','0'),(839,1,3377,'1','1','1','1'),(840,2,3377,'0','0','0','0'),(841,4,3377,'0','0','0','0'),(842,1,3378,'1','1','1','1'),(843,2,3378,'0','0','0','0'),(844,4,3378,'0','0','0','0'),(845,1,3379,'1','1','1','1'),(846,2,3379,'0','0','0','0'),(847,4,3379,'0','0','0','0'),(848,1,3381,'1','1','1','1'),(849,2,3381,'0','0','0','0'),(850,4,3381,'0','0','0','0'),(851,1,3382,'1','1','1','1'),(852,2,3382,'0','0','0','0'),(853,4,3382,'0','0','0','0'),(854,1,3383,'1','1','1','1'),(855,2,3383,'0','0','0','0'),(856,4,3383,'0','0','0','0'),(857,1,3384,'1','1','1','1'),(858,2,3384,'0','0','0','0'),(859,4,3384,'0','0','0','0'),(860,1,3385,'1','1','1','1'),(861,2,3385,'0','0','0','0'),(862,4,3385,'0','0','0','0'),(863,1,3387,'1','1','1','1'),(864,2,3387,'0','0','0','0'),(865,4,3387,'0','0','0','0'),(866,1,3388,'1','1','1','1'),(867,2,3388,'0','0','0','0'),(868,4,3388,'0','0','0','0'),(869,1,3389,'1','1','1','1'),(870,2,3389,'0','0','0','0'),(871,4,3389,'0','0','0','0'),(872,1,3390,'1','1','1','1'),(873,2,3390,'0','0','0','0'),(874,4,3390,'0','0','0','0'),(875,1,3391,'1','1','1','1'),(876,2,3391,'0','0','0','0'),(877,4,3391,'0','0','0','0'),(878,1,3392,'1','1','1','1'),(879,2,3392,'0','0','0','0'),(880,4,3392,'0','0','0','0'),(881,1,3393,'1','1','1','1'),(882,2,3393,'0','0','0','0'),(883,4,3393,'0','0','0','0');
/*!40000 ALTER TABLE `aros_acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batches`
--

DROP TABLE IF EXISTS `batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expire_date` datetime DEFAULT NULL,
  `batch_number` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
INSERT INTO `batches` VALUES (1,'2012-05-13 19:06:00','0125365X1'),(2,'2012-08-14 00:00:00','55556666');
/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `category` varchar(40) DEFAULT NULL,
  `units` varchar(40) DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  `presentation` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'KIT','Kit',NULL,NULL,NULL,'');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
INSERT INTO `levels` VALUES (10,'NCC'),(20,'CH'),(30,'PHC'),(40,'RH');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `shortname` varchar(4) NOT NULL,
  `locationLatitude` varchar(13) NOT NULL,
  `locationLongitude` varchar(13) NOT NULL,
  `deleted` int(1) DEFAULT '0',
  `level_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'ClÃ­nica San Roque RH','CSR','-28.566667','-58.716667',0,40,5),(3,'Hospital de Mercedes (CH) ','MCH','-29.2','-58.083333',0,20,11),(5,'Colonia San MartÃ­n','SMT','-28.183333','-56.65',0,30,3),(6,'Hospital Santa LucÃ­a PHC','HSL','-28.983333','-59.1',0,30,3),(9,'Hospital ConcepciÃ³n PHC','HCP','-28.366667','-57.866667',0,30,3),(10,'ClÃ­nica Saladas RH','CSL','-28.25','-58.616667',0,40,9),(11,'IECS','NCC','-34.603333','-58.381667',0,10,0);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messagereceiveds`
--

DROP TABLE IF EXISTS `messagereceiveds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messagereceiveds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `rawmessage` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messagereceiveds`
--

LOCK TABLES `messagereceiveds` WRITE;
/*!40000 ALTER TABLE `messagereceiveds` DISABLE KEYS */;
INSERT INTO `messagereceiveds` VALUES (1,47,'2011-10-04 15:22:00','S 25 NCC 0731221221'),(3,49,'2011-10-04 15:22:12','S 25 NCC 0724008266'),(5,49,'2011-10-04 15:22:24','S 25 NCC 0724008266'),(7,49,'2011-10-04 15:22:35','S 25 NCC  27724008266'),(9,27,'2011-10-04 15:23:20','S 25 NCC  27724008226'),(11,27,'2011-10-04 15:23:28','S 25 NCCP  27724008226'),(13,27,'2011-10-04 15:23:38','S 25  27724008226'),(15,27,'2011-10-04 15:23:46','S 2555555  27724008226'),(17,27,'2011-10-04 16:09:47','S 2555555  27724008226'),(19,27,'2011-10-04 16:12:51','S 2555555  27724008226'),(21,27,'2011-10-04 16:13:02','S 43184111  27724008226'),(23,27,'2011-10-04 16:13:12','C 43184111  27724008226'),(25,27,'2011-10-04 16:13:17','S 43184111  27724008226'),(27,27,'2011-10-04 16:13:26','R 43184111  27724008226'),(29,27,'2011-10-04 16:13:38','R 50  27724008226'),(31,27,'2011-10-04 16:14:01','S 50 mch  27724008226'),(33,27,'2011-10-04 16:14:08','S 50 mchl  27724008226'),(35,27,'2011-10-04 16:14:19','e 5  27724008226'),(37,27,'2011-10-04 16:14:24','e  27724008226'),(39,27,'2011-10-04 16:14:30','S  27724008226'),(41,27,'2011-10-04 16:14:35','r  27724008226'),(43,27,'2011-10-04 16:14:42','ew  27724008226'),(45,27,'2011-10-04 16:14:49','ew 30  27724008226'),(47,27,'2011-10-04 16:14:59','ew 30 ncc  27724008226'),(49,4,'2011-10-04 16:20:45','e 2 3774983994'),(77,3,'2012-05-14 15:57:56','3773234003,125362,CSRo'),(76,3,'2012-05-14 15:53:00','3773234003,125362'),(75,3,'2012-05-14 15:50:48','3773234003,125362'),(74,3,'2012-05-14 15:48:59','3773234003,125362'),(73,60,'2012-05-14 15:48:00','22222,125362'),(72,60,'2012-05-14 14:59:58','22222,125362'),(71,NULL,'2012-05-14 14:57:37','1111111,125362'),(70,3,'2012-05-14 14:56:23','3773234003,125362'),(69,58,'2012-05-14 14:40:39','12222,125362');
/*!40000 ALTER TABLE `messagereceiveds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messagesents`
--

DROP TABLE IF EXISTS `messagesents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messagesents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messagereceived_id` int(11) DEFAULT NULL,
  `phone_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `rawmessage` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messagesents`
--

LOCK TABLES `messagesents` WRITE;
/*!40000 ALTER TABLE `messagesents` DISABLE KEYS */;
INSERT INTO `messagesents` VALUES (1,1,47,'2011-10-04 15:22:00','Error: phone number 0731221221 not found in database. It has been added but you won\'t be able to enter data until you request activation\n'),(3,3,49,'2011-10-04 15:22:12','Error: phone number 0724008266 not found in database. It has been added but you won\'t be able to enter data until you request activation\n'),(5,5,49,'2011-10-04 15:22:24','Error: phone number 0724008266 is not active. You won\'t be able to enter data until you request activation\n'),(7,7,49,'2011-10-04 15:22:35','Error: phone number  27724008266 is not active. You won\'t be able to enter data until you request activation\n'),(9,9,27,'2011-10-04 15:23:20','Message processed successfully. Qty: 25'),(11,11,27,'2011-10-04 15:23:28','Cannot find facility with code \'NCCP\'. Message not processed\n'),(13,13,27,'2011-10-04 15:23:38','Incorrect report format. Not processed'),(15,15,27,'2011-10-04 15:23:46','Incorrect report format. Not processed'),(17,17,27,'2011-10-04 16:09:47','Incorrect report format. Please correct and resend'),(19,19,27,'2011-10-04 16:12:51','Cannot find patient with number \'2555555\'. Message not processed\n'),(21,21,27,'2011-10-04 16:13:02','Patient has not given consent \'43184111\'. Message not processed\n'),(23,23,27,'2011-10-04 16:13:12','Patient has been \'updated\'\n'),(25,25,27,'2011-10-04 16:13:17','Message processed successfully. Qty: 1'),(27,27,27,'2011-10-04 16:13:26','Message processed successfully. Qty: 1'),(29,29,27,'2011-10-04 16:13:38','Message processed successfully. Qty: 50'),(31,31,27,'2011-10-04 16:14:01','Message processed successfully. Qty: 50'),(33,33,27,'2011-10-04 16:14:08','Cannot find facility with code \'MCHL\'. Message not processed\n'),(35,35,27,'2011-10-04 16:14:19','Message processed successfully. Qty: 5'),(37,37,27,'2011-10-04 16:14:24','Incorrect report format. Please correct and resend'),(39,39,27,'2011-10-04 16:14:30','Incorrect report format. Please correct and resend'),(41,41,27,'2011-10-04 16:14:35','Incorrect report format. Please correct and resend'),(43,43,27,'2011-10-04 16:14:42','Incorrect report format. Please correct and resend'),(45,45,27,'2011-10-04 16:14:49','Cannot find action ew. Please verify and resend\n'),(47,47,27,'2011-10-04 16:14:59','Cannot find action ew. Please verify and resend\n'),(49,49,4,'2011-10-04 16:20:45','Message processed successfully. Qty: 2');
/*!40000 ALTER TABLE `messagesents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modifiers`
--

DROP TABLE IF EXISTS `modifiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modifiers` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modifiers`
--

LOCK TABLES `modifiers` WRITE;
/*!40000 ALTER TABLE `modifiers` DISABLE KEYS */;
INSERT INTO `modifiers` VALUES (1,'+'),(2,'-'),(3,'=');
/*!40000 ALTER TABLE `modifiers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(35) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `consent` int(1) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pateintnumberuniq` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,'43184111','2011-10-04 16:13:12',1,NULL),(2,'43185111',NULL,1,NULL),(3,'43181234','2011-07-07 10:50:16',1,NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phonenumber` varchar(12) DEFAULT NULL,
  `active` int(1) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
INSERT INTO `phones` VALUES (3,'3773234003',1,1,'Teobaldo',0),(4,'3774983994',1,6,'Mercucio',0),(5,'3773452963',0,9,'Benvolio',0),(6,'3773004212',1,3,'Lorenzo',0),(8,'3773998604',1,3,'Rosalina',0),(27,'+27724008226',1,3,'Pedro',0),(28,'+19193604046',1,5,'Baltasar',0),(35,'+54911675763',0,NULL,'Unknown',1),(37,'+54111234567',0,NULL,'Unknown',1),(39,'1167576323',1,3,'Pablo',0),(41,'1158570687',1,11,'Eugenia',0),(43,'3773493090',1,3,'3773493090',1),(45,'+54911646237',0,NULL,'Unknown',0),(47,'0731221221',0,NULL,'Unknown',0),(49,'0724008266',0,NULL,'Unknown',0),(50,NULL,0,NULL,'',0),(51,NULL,0,NULL,'',0),(52,NULL,0,NULL,'',0),(53,'3773234011',0,NULL,'Unknown',0),(54,'3773234012',0,NULL,'Unknown',0),(55,'1122334433',1,NULL,'Unknown',0),(56,'12123252',0,NULL,'Unknown',0),(57,'3773004012',0,NULL,'Unknown',0),(58,'12222',0,NULL,'Unknown',0),(59,'1111111',0,NULL,'Unknown',0),(60,'22222',0,3,'Unknown',0);
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rest_logs`
--

DROP TABLE IF EXISTS `rest_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rest_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `model_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `requested` datetime NOT NULL,
  `apikey` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `httpcode` smallint(3) unsigned NOT NULL,
  `error` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ratelimited` tinyint(1) unsigned NOT NULL,
  `data_in` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_out` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `responded` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rest_logs`
--

LOCK TABLES `rest_logs` WRITE;
/*!40000 ALTER TABLE `rest_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `rest_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator',NULL,'2011-09-01 16:20:09'),(2,'Moderator',NULL,'2011-09-01 11:27:16'),(3,'User',NULL,'2011-09-01 11:27:28');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(5) NOT NULL,
  `created` datetime NOT NULL,
  `phone_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `quantity_after` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `messagereceived_id` int(11) DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sent_to` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
INSERT INTO `stats` VALUES (1,25,'2011-09-27 17:13:34',0,1,25,1,NULL,NULL,1,NULL,1,NULL),(3,5,'2011-09-28 06:54:29',0,1,20,1,NULL,NULL,1,NULL,2,NULL),(4,5,'2011-09-28 06:55:56',0,1,15,1,NULL,NULL,1,6,2,NULL),(5,4,'2011-09-28 08:37:45',0,6,4,1,NULL,NULL,2,NULL,1,NULL),(6,2,'2011-09-28 08:41:16',0,6,2,1,NULL,NULL,2,5,2,NULL),(7,5,'2011-09-28 08:47:18',0,6,-3,1,NULL,NULL,2,3,2,NULL),(8,1,'2011-09-28 11:39:15',0,3,-1,1,NULL,NULL,1,NULL,3,NULL),(9,2,'2011-09-28 12:24:34',0,3,-3,1,NULL,NULL,1,10,2,NULL),(10,1,'2011-09-28 15:43:22',0,3,-4,1,NULL,NULL,1,NULL,2,1),(11,1,'2011-09-28 16:24:26',0,3,-3,1,NULL,NULL,1,NULL,1,3),(12,1,'2011-09-28 16:26:22',0,3,-4,1,NULL,NULL,1,NULL,2,1),(13,5,'2011-09-28 16:27:11',0,3,1,1,NULL,NULL,1,NULL,1,NULL),(15,15,'2011-09-30 04:39:13',0,3,16,1,NULL,NULL,1,NULL,1,NULL),(17,25,'2011-10-04 15:23:20',27,3,-9,1,9,NULL,NULL,11,2,NULL),(19,1,'2011-10-04 16:13:17',27,3,-10,1,25,NULL,NULL,NULL,2,1),(21,1,'2011-10-04 16:13:26',27,3,-9,1,27,NULL,NULL,NULL,1,1),(23,50,'2011-10-04 16:13:38',27,3,41,1,29,NULL,NULL,NULL,1,NULL),(25,50,'2011-10-04 16:14:01',27,3,-9,1,31,NULL,NULL,3,2,NULL),(27,5,'2011-10-04 16:14:19',27,3,-14,1,35,NULL,NULL,NULL,3,NULL),(29,2,'2011-10-04 16:20:45',4,6,-5,1,49,NULL,NULL,NULL,3,NULL);
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statuses`
--

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;
INSERT INTO `statuses` VALUES (1,'Receive'),(2,'Assign'),(3,'Discard'),(4,'Return'),(5,'Destroy');
/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'125362',1);
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units_items`
--

DROP TABLE IF EXISTS `units_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units_items`
--

LOCK TABLES `units_items` WRITE;
/*!40000 ALTER TABLE `units_items` DISABLE KEYS */;
INSERT INTO `units_items` VALUES (1,1,1);
/*!40000 ALTER TABLE `units_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,2,1);
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `reach` int(11) DEFAULT NULL,
  `phone_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','8db57f058f3daac554698a07b8eaf8df1e1d319b',1,'2010-08-29 23:11:29','2011-09-28 11:58:13',4,NULL,3,1),(2,'Moderator','moderator','972b31ee3bed7b8b8f965bbc967dcc4a4d8b67b5',2,'2010-08-29 23:11:44','2011-09-28 08:35:44',0,NULL,6,1),(4,'Name','pablo','f4d7601f15ced1f5a7c75c43e3e348da0101fd30',2,'2010-08-30 18:02:15','2011-07-05 11:55:02',0,NULL,1,1),(6,'Name','pdestefanis','f4d7601f15ced1f5a7c75c43e3e348da0101fd30',2,'2010-09-16 09:59:10','2011-07-05 11:55:16',0,NULL,1,1),(7,'User name ','user','6a694882e95141fab40d4170356884698ceb88b6',3,'2011-07-04 08:45:10','2011-10-05 11:24:18',0,NULL,11,1),(9,'Diglio Simoni','dsimoni','c05d437bece8c7c84f2a547406cc2a2a9a98cbc2',2,'2012-03-22 17:06:05','2012-03-22 17:06:22',4,NULL,1,1),(10,'Caleb Bell','caleb','575da4ed18e9751a6ceff9a527499c9efd54008a',2,'2012-04-10 11:33:17','2012-04-10 11:33:17',4,45,1,1);
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

-- Dump completed on 2012-05-14 17:13:23
