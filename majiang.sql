/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50640
 Source Host           : localhost:3306
 Source Schema         : majiang

 Target Server Type    : MySQL
 Target Server Version : 50640
 File Encoding         : 65001

 Date: 30/11/2018 17:41:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for match
-- ----------------------------
DROP TABLE IF EXISTS `match`;
CREATE TABLE `match`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `table` int(11) UNSIGNED NULL DEFAULT NULL,
  `east` int(11) NULL DEFAULT NULL,
  `south` int(11) NULL DEFAULT NULL,
  `weat` int(11) NULL DEFAULT NULL,
  `north` int(11) NULL DEFAULT NULL,
  `winner` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `win_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `loser` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lose_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `multiple` int(5) UNSIGNED NULL DEFAULT NULL,
  `check` smallint(2) UNSIGNED NULL DEFAULT NULL,
  `lock` smallint(2) UNSIGNED NULL DEFAULT NULL,
  `created_at` int(11) UNSIGNED NULL DEFAULT NULL,
  `updated_at` int(11) UNSIGNED NULL DEFAULT NULL,
  `check_at` int(11) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for table
-- ----------------------------
DROP TABLE IF EXISTS `table`;
CREATE TABLE `table`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `east` int(11) UNSIGNED NOT NULL,
  `south` int(11) UNSIGNED NOT NULL,
  `weat` int(11) UNSIGNED NOT NULL,
  `north` int(11) UNSIGNED NOT NULL,
  `point` int(11) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
