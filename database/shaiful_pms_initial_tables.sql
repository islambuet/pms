/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : shaiful_pms

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-06-26 15:36:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pms_history`
-- ----------------------------
DROP TABLE IF EXISTS `pms_history`;
CREATE TABLE `pms_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) DEFAULT NULL,
  `table_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `data` varchar(255) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms_history
-- ----------------------------
INSERT INTO `pms_history` VALUES ('1', 'sys_module_task', '5', 'pms_system_task', '{\"name\":\"Assign User To Group\",\"type\":\"TASK\",\"parent\":\"1\",\"controller\":\"Sys_assign_user_group\",\"ordering\":\"4\",\"status\":\"Active\",\"user_created\":\"1\",\"date_created\":1466929864}', '1', 'INSERT', '1466929864');
INSERT INTO `pms_history` VALUES ('2', 'sys_module_task', '6', 'pms_system_task', '{\"name\":\"Site Offline\",\"type\":\"TASK\",\"parent\":\"1\",\"controller\":\"Sys_site_offline\",\"ordering\":\"5\",\"status\":\"Active\",\"user_created\":\"1\",\"date_created\":1466929894}', '1', 'INSERT', '1466929894');
INSERT INTO `pms_history` VALUES ('3', 'sys_user_role', '8', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"task_id\":2,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933158}', '1', 'INSERT', '1466933158');
INSERT INTO `pms_history` VALUES ('4', 'sys_user_role', '9', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"task_id\":3,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933158}', '1', 'INSERT', '1466933158');
INSERT INTO `pms_history` VALUES ('5', 'sys_user_role', '10', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"task_id\":4,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933158}', '1', 'INSERT', '1466933158');
INSERT INTO `pms_history` VALUES ('6', 'sys_user_role', '11', 'pms_system_user_group_role', '{\"view\":0,\"add\":0,\"edit\":0,\"delete\":0,\"task_id\":5,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933158}', '1', 'INSERT', '1466933158');
INSERT INTO `pms_history` VALUES ('7', 'sys_user_role', '12', 'pms_system_user_group_role', '{\"view\":0,\"add\":0,\"edit\":0,\"delete\":0,\"task_id\":6,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933158}', '1', 'INSERT', '1466933158');
INSERT INTO `pms_history` VALUES ('8', 'sys_user_role', '13', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":0,\"download\":0,\"column_headers\":0,\"sp1\":0,\"sp2\":0,\"sp3\":0,\"sp4\":0,\"sp5\":0,\"task_id\":2,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933468}', '1', 'INSERT', '1466933468');
INSERT INTO `pms_history` VALUES ('9', 'sys_user_role', '14', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":0,\"download\":0,\"column_headers\":0,\"sp1\":0,\"sp2\":0,\"sp3\":0,\"sp4\":0,\"sp5\":0,\"task_id\":3,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933468}', '1', 'INSERT', '1466933468');
INSERT INTO `pms_history` VALUES ('10', 'sys_user_role', '15', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":0,\"download\":0,\"column_headers\":0,\"sp1\":0,\"sp2\":0,\"sp3\":0,\"sp4\":0,\"sp5\":0,\"task_id\":4,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933468}', '1', 'INSERT', '1466933468');
INSERT INTO `pms_history` VALUES ('11', 'sys_user_role', '16', 'pms_system_user_group_role', '{\"view\":1,\"add\":0,\"edit\":0,\"delete\":0,\"print\":0,\"download\":0,\"column_headers\":0,\"sp1\":1,\"sp2\":0,\"sp3\":0,\"sp4\":0,\"sp5\":0,\"task_id\":5,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933468}', '1', 'INSERT', '1466933468');
INSERT INTO `pms_history` VALUES ('12', 'sys_user_role', '17', 'pms_system_user_group_role', '{\"view\":1,\"add\":0,\"edit\":0,\"delete\":0,\"print\":0,\"download\":0,\"column_headers\":0,\"sp1\":0,\"sp2\":0,\"sp3\":0,\"sp4\":0,\"sp5\":1,\"task_id\":6,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933468}', '1', 'INSERT', '1466933468');
INSERT INTO `pms_history` VALUES ('13', 'sys_user_role', '18', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":2,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933483}', '1', 'INSERT', '1466933483');
INSERT INTO `pms_history` VALUES ('14', 'sys_user_role', '19', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":3,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933483}', '1', 'INSERT', '1466933483');
INSERT INTO `pms_history` VALUES ('15', 'sys_user_role', '20', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":4,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933483}', '1', 'INSERT', '1466933483');
INSERT INTO `pms_history` VALUES ('16', 'sys_user_role', '21', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":5,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933483}', '1', 'INSERT', '1466933483');
INSERT INTO `pms_history` VALUES ('17', 'sys_user_role', '22', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":6,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933483}', '1', 'INSERT', '1466933483');
INSERT INTO `pms_history` VALUES ('18', 'sys_user_role', '1', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":2,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933513}', '1', 'INSERT', '1466933513');
INSERT INTO `pms_history` VALUES ('19', 'sys_user_role', '2', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":3,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933513}', '1', 'INSERT', '1466933513');
INSERT INTO `pms_history` VALUES ('20', 'sys_user_role', '3', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":4,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933513}', '1', 'INSERT', '1466933513');
INSERT INTO `pms_history` VALUES ('21', 'sys_user_role', '4', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":5,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933513}', '1', 'INSERT', '1466933513');
INSERT INTO `pms_history` VALUES ('22', 'sys_user_role', '5', 'pms_system_user_group_role', '{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"print\":1,\"download\":1,\"column_headers\":1,\"sp1\":1,\"sp2\":1,\"sp3\":1,\"sp4\":1,\"sp5\":1,\"task_id\":6,\"user_group_id\":\"1\",\"user_created\":\"1\",\"date_created\":1466933513}', '1', 'INSERT', '1466933513');
INSERT INTO `pms_history` VALUES ('23', 'sys_user_group', '3', 'pms_system_user_group', '{\"name\":\"asdf\",\"ordering\":\"99\",\"user_created\":\"1\",\"date_created\":1466933564}', '1', 'INSERT', '1466933564');
INSERT INTO `pms_history` VALUES ('24', 'sys_assign_user_group', '3', 'pms_system_assigned_group', '{\"user_id\":\"1\",\"user_group\":\"2\",\"user_created\":\"1\",\"date_created\":1466933574,\"revision\":1}', '1', 'INSERT', '1466933574');
INSERT INTO `pms_history` VALUES ('25', 'sys_site_offline', '1', 'pms_system_site_offline', '{\"status\":\"Active\",\"user_created\":\"1\",\"date_created\":1466933669}', '1', 'INSERT', '1466933669');

-- ----------------------------
-- Table structure for `pms_history_hack`
-- ----------------------------
DROP TABLE IF EXISTS `pms_history_hack`;
CREATE TABLE `pms_history_hack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT 'Active',
  `action_id` int(11) DEFAULT '99',
  `other_info` text,
  `date_created` int(11) DEFAULT '0',
  `date_created_string` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms_history_hack
-- ----------------------------

-- ----------------------------
-- Table structure for `pms_system_assigned_group`
-- ----------------------------
DROP TABLE IF EXISTS `pms_system_assigned_group`;
CREATE TABLE `pms_system_assigned_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_group` int(11) NOT NULL,
  `revision` int(4) NOT NULL DEFAULT '1',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `user_created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms_system_assigned_group
-- ----------------------------
INSERT INTO `pms_system_assigned_group` VALUES ('1', '1', '1', '1', '0', '0');

-- ----------------------------
-- Table structure for `pms_system_site_offline`
-- ----------------------------
DROP TABLE IF EXISTS `pms_system_site_offline`;
CREATE TABLE `pms_system_site_offline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `user_created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of pms_system_site_offline
-- ----------------------------

-- ----------------------------
-- Table structure for `pms_system_task`
-- ----------------------------
DROP TABLE IF EXISTS `pms_system_task`;
CREATE TABLE `pms_system_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'TASK',
  `parent` int(11) NOT NULL DEFAULT '0',
  `controller` varchar(500) NOT NULL,
  `ordering` smallint(6) NOT NULL DEFAULT '9999',
  `icon` varchar(255) NOT NULL DEFAULT 'menu.png',
  `status` varchar(11) NOT NULL DEFAULT 'Active',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `user_created` int(11) NOT NULL DEFAULT '0',
  `date_updated` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms_system_task
-- ----------------------------
INSERT INTO `pms_system_task` VALUES ('1', 'System Settings', 'MODULE', '0', '', '1', 'menu.png', 'Active', '1455625924', '1', '1455625924', '1');
INSERT INTO `pms_system_task` VALUES ('2', 'Module & Task', 'TASK', '1', 'Sys_module_task', '1', 'menu.png', 'Active', '1455625924', '1', '1455625924', '1');
INSERT INTO `pms_system_task` VALUES ('3', 'User Role', 'TASK', '1', 'Sys_user_role', '2', 'menu.png', 'Active', '1455625924', '1', '1455625924', '1');
INSERT INTO `pms_system_task` VALUES ('4', 'User Group', 'TASK', '1', 'Sys_user_group', '3', 'menu.png', 'Active', '1455625924', '1', '1455625924', '1');
INSERT INTO `pms_system_task` VALUES ('5', 'Assign User To Group', 'TASK', '1', 'Sys_assign_user_group', '4', 'menu.png', 'Active', '1466929864', '1', null, null);
INSERT INTO `pms_system_task` VALUES ('6', 'Site Offline', 'TASK', '1', 'Sys_site_offline', '5', 'menu.png', 'Active', '1466929894', '1', null, null);

-- ----------------------------
-- Table structure for `pms_system_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `pms_system_user_group`;
CREATE TABLE `pms_system_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` varchar(11) NOT NULL DEFAULT 'Active',
  `ordering` tinyint(4) NOT NULL DEFAULT '99',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `user_created` int(11) NOT NULL DEFAULT '0',
  `date_updated` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms_system_user_group
-- ----------------------------
INSERT INTO `pms_system_user_group` VALUES ('1', 'Super Admin', 'Active', '1', '1455625924', '1', '1455625924', '1');
INSERT INTO `pms_system_user_group` VALUES ('2', 'Admin', 'Active', '2', '1455777728', '1', null, null);

-- ----------------------------
-- Table structure for `pms_system_user_group_role`
-- ----------------------------
DROP TABLE IF EXISTS `pms_system_user_group_role`;
CREATE TABLE `pms_system_user_group_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `view` tinyint(4) NOT NULL DEFAULT '0',
  `add` tinyint(4) NOT NULL DEFAULT '0',
  `edit` tinyint(4) NOT NULL DEFAULT '0',
  `delete` tinyint(4) NOT NULL DEFAULT '0',
  `print` tinyint(2) NOT NULL DEFAULT '0',
  `download` tinyint(2) NOT NULL DEFAULT '0',
  `column_headers` tinyint(2) NOT NULL DEFAULT '0',
  `sp1` tinyint(2) DEFAULT '0',
  `sp2` tinyint(2) DEFAULT '0',
  `sp3` tinyint(2) DEFAULT '0',
  `sp4` tinyint(2) DEFAULT '0',
  `sp5` tinyint(2) DEFAULT '0',
  `revision` int(11) NOT NULL DEFAULT '1',
  `date_created` int(11) NOT NULL DEFAULT '0',
  `user_created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pms_system_user_group_role
-- ----------------------------
INSERT INTO `pms_system_user_group_role` VALUES ('1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1466933513', '1');
INSERT INTO `pms_system_user_group_role` VALUES ('2', '1', '3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1466933513', '1');
INSERT INTO `pms_system_user_group_role` VALUES ('3', '1', '4', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1466933513', '1');
INSERT INTO `pms_system_user_group_role` VALUES ('4', '1', '5', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1466933513', '1');
INSERT INTO `pms_system_user_group_role` VALUES ('5', '1', '6', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1466933513', '1');
