/*
Navicat MySQL Data Transfer

Source Server         : Localhost-Mysql
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : educate

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2018-06-14 10:52:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for edu_admin
-- ----------------------------
DROP TABLE IF EXISTS `edu_admin`;
CREATE TABLE `edu_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 后台 2 教师',
  `teacher_id` int(11) DEFAULT NULL COMMENT '教师ID',
  `role_name` varchar(255) NOT NULL COMMENT '昵称',
  `user_name` varchar(50) NOT NULL COMMENT '用户名/账号',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `email` varchar(30) NOT NULL COMMENT '邮箱',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 显示 2、删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of edu_admin
-- ----------------------------
INSERT INTO `edu_admin` VALUES ('1', '1', null, '总管理员', 'admin', '519475228fe35ad067744465c42a19b2', '', '', '1', '2018-06-08 14:31:04');
INSERT INTO `edu_admin` VALUES ('2', '2', '4', '朱涛', 'zhutao', 'd38c29dd40ad288376dba6de68a2d468', '15262997833', '418695877@qq.com', '1', '2018-06-08 14:31:08');
INSERT INTO `edu_admin` VALUES ('3', '2', '2', '阿涛', 'taotao', '519475228fe35ad067744465c42a19b2', '15262997833', '66666@qq.com', '1', '2018-06-08 14:31:10');
INSERT INTO `edu_admin` VALUES ('4', '2', '2', '阿涛', 'nana', '519475228fe35ad067744465c42a19b2', '15262997833', '66666@qq.com', '1', '2018-06-08 14:31:56');

-- ----------------------------
-- Table structure for edu_banner
-- ----------------------------
DROP TABLE IF EXISTS `edu_banner`;
CREATE TABLE `edu_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1、首页banner',
  `pic` varchar(255) NOT NULL COMMENT '图片',
  `link` varchar(500) NOT NULL DEFAULT '#' COMMENT 'banner链接',
  `sort_index` mediumint(5) NOT NULL DEFAULT '0' COMMENT '排序序号',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='banner表';

-- ----------------------------
-- Records of edu_banner
-- ----------------------------
INSERT INTO `edu_banner` VALUES ('1', '1', 'http://educate.com/Upload/images/20180611/f80dfaf53098f86e.jpg', 'http://www.baidu.com', '1', '2018-06-11 16:05:27');
INSERT INTO `edu_banner` VALUES ('3', '1', 'http://educate.com/Upload/images/20180611/18ccfebe8d8f15c0.jpg', 'http://taobao.com', '2', '2018-06-11 16:05:33');
INSERT INTO `edu_banner` VALUES ('4', '1', 'http://educate.com/Upload/images/20180611/93bca2729d1aa09f.jpg', 'http://jd.com', '3', '2018-06-11 16:05:35');

-- ----------------------------
-- Table structure for edu_comment
-- ----------------------------
DROP TABLE IF EXISTS `edu_comment`;
CREATE TABLE `edu_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 评论老师 2 评论课程',
  `relation_id` int(11) NOT NULL COMMENT '评论课程即课程ID 评论老师即老师ID',
  `star` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评论星级',
  `content` text NOT NULL COMMENT '评论内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 显示 2 不显示',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of edu_comment
-- ----------------------------
INSERT INTO `edu_comment` VALUES ('1', '3', '1', '4', '4', '这么女老师太漂亮啦，讲课也十分精彩，她的课我从来就没逃过', '1', '2018-06-07 17:35:49');
INSERT INTO `edu_comment` VALUES ('2', '3', '2', '1', '5', '自从听了这节课，我茅塞顿开，三观端正了，做人踏实了，处事稳重了', '1', '2018-06-07 17:36:53');
INSERT INTO `edu_comment` VALUES ('3', '3', '2', '1', '4', '学了这个课，感觉身体素质都有提高，特别是老师满专业的，下次还回来', '1', '2018-06-07 17:36:53');

-- ----------------------------
-- Table structure for edu_course
-- ----------------------------
DROP TABLE IF EXISTS `edu_course`;
CREATE TABLE `edu_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL COMMENT '老师ID',
  `type` mediumint(5) NOT NULL COMMENT '课程类型ID',
  `name` varchar(50) NOT NULL COMMENT '课程名称',
  `price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格',
  `pics` text NOT NULL COMMENT '图片组',
  `link` varchar(255) NOT NULL COMMENT '视频链接',
  `profile` text NOT NULL COMMENT '课程简介',
  `content` text NOT NULL COMMENT '课程详情',
  `tags` text COMMENT '标签',
  `sale_count` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `is_stick` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1 置顶 2 不置顶',
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1 上架/审核通过 2 下架 3 待审核 4 审核不通过 5.删除',
  `check_time` datetime DEFAULT NULL COMMENT '审核通过的时间',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='课程表';

-- ----------------------------
-- Records of edu_course
-- ----------------------------
INSERT INTO `edu_course` VALUES ('1', '4', '4', '口琴精进班', '988.00', 'a:1:{i:0;s:62:\"http://educate.com/Upload/images/20180612/82bdd32824ee6e5c.jpg\";}', 'http://47.95.208.179:3000/void/qianxun/video1.mp4', '上课时间自由，课程性价比高，欢迎加入', '&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； &amp;nbsp； 感恩先人开创的“茶马古道”，让我得以世界闻名，但从来，最好的我们&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;永远是宫廷贡品除了皇宫贵族就是赠送到国外。我认为，我应该属于更&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;多人，特别是没有背景，通过自己努力获得成功的人，人类，是靠一代&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;又一代的人前赴后继才取得进步的，你们都是世界的功臣。所以，我认&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;为你们更值得拥有。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； 世界上最奢侈的艺术品，是你独一无二的人生，&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； 世界遗产诉说不尽的，是你的人生故事。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； 所以，我愿意为你镌刻人生的点滴，我愿意为你诉说关于你的每段故事。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808034807448.png&quot; title=&quot;1528808034807448.png&quot; alt=&quot;img1-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808045104283.png&quot; title=&quot;1528808045104283.png&quot; alt=&quot;img2-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 'a:3:{i:0;s:9:\"高大上\";i:1;s:12:\"老师负责\";i:2;s:12:\"幽默风趣\";}', '0', '1', '1', '2018-06-14 00:36:59', '2018-06-06 10:17:04');
INSERT INTO `edu_course` VALUES ('2', '4', '8', '茶艺精品课', '1500.00', 'a:1:{i:0;s:62:\"http://educate.com/Upload/images/20180612/9d4c9a8a9f9a7d61.jpg\";}', 'http://47.95.208.179:3000/void/qianxun/video1.mp4', '精品茶道，等您到来', '&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； &amp;nbsp； 感恩先人开创的“茶马古道”，让我得以世界闻名，但从来，最好的我们&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;永远是宫廷贡品除了皇宫贵族就是赠送到国外。我认为，我应该属于更&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;多人，特别是没有背景，通过自己努力获得成功的人，人类，是靠一代&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;又一代的人前赴后继才取得进步的，你们都是世界的功臣。所以，我认&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;为你们更值得拥有。&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； 世界上最奢侈的艺术品，是你独一无二的人生，&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； 世界遗产诉说不尽的，是你的人生故事。&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;&amp;nbsp； &amp;nbsp； &amp;nbsp； 所以，我愿意为你镌刻人生的点滴，我愿意为你诉说关于你的每段故事。&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;&lt;img src=&quot;/Upload/ueditor/Public/upload/ueditor/20180612/1528808034807448.png&quot; title=&quot;1528808034807448.png&quot; alt=&quot;img1-icon.png&quot;/&gt;&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot; style=&quot;position: relative； z-index: 2048； font-family: Menlo, Consolas, Courier, monospace； font-size: 12px；&quot;&gt;&lt;span class=&quot;inner-text&quot; data-type=&quot;node-value&quot; style=&quot;color: rgb(34, 34, 34)； position: relative； z-index: 2048；&quot;&gt;&lt;img src=&quot;/Upload/ueditor/Public/upload/ueditor/20180612/1528808045104283.png&quot; title=&quot;1528808045104283.png&quot; alt=&quot;img2-icon.png&quot;/&gt;&lt;/span&gt;&lt;/span&gt;&lt;/p&gt;', 'a:2:{i:0;s:12:\"直戳重点\";i:1;s:6:\"精彩\";}', '1', '1', '1', '2018-06-14 00:37:01', '2018-06-08 10:17:04');
INSERT INTO `edu_course` VALUES ('3', '4', '4', '长笛入门课', '500.00', 'a:1:{i:0;s:62:\"http://educate.com/Upload/images/20180612/ed32e3a8876efd1f.jpg\";}', 'http://47.95.208.179:3000/void/qianxun/video1.mp4', '包教包会，每个人都是艺术的宠儿', '&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；quot；感恩先人开创的“茶马古道”，让我得以世界闻名，但从来，最好的我们&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；永远是宫廷贡品除了皇宫贵族就是赠送到国外。我认为，我应该属于更&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；多人，特别是没有背景，通过自己努力获得成功的人，人类，是靠一代&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；又一代的人前赴后继才取得进步的，你们都是世界的功臣。所以，我认&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；为你们更值得拥有。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；世界上最奢侈的艺术品，是你独一无二的人生，&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；世界遗产诉说不尽的，是你的人生故事。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；所以，我愿意为你镌刻人生的点滴，我愿意为你诉说关于你的每段故事。&amp;amp；amp；quot；&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808034807448.png&quot; title=&quot;1528808034807448.png&quot; alt=&quot;img1-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808045104283.png&quot; title=&quot;1528808045104283.png&quot; alt=&quot;img2-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 'a:2:{i:0;s:9:\"有干货\";i:1;s:12:\"引人入胜\";}', '0', '1', '1', '2018-06-12 23:52:04', '2018-06-08 10:17:04');
INSERT INTO `edu_course` VALUES ('4', '4', '2', '少儿厨房', '588.00', 'a:3:{i:0;s:62:\"http://educate.com/Upload/images/20180612/a11e37c123bcc609.png\";i:1;s:62:\"http://educate.com/Upload/images/20180612/03f2f5ce1d927077.png\";i:2;s:62:\"http://educate.com/Upload/images/20180612/fe2cd53b59727ac5.png\";}', 'http://47.95.208.179:3000/void/qianxun/video1.mp4', '做饭，要从小做起', '&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；quot；感恩先人开创的“茶马古道”，让我得以世界闻名，但从来，最好的我们&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；永远是宫廷贡品除了皇宫贵族就是赠送到国外。我认为，我应该属于更&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；多人，特别是没有背景，通过自己努力获得成功的人，人类，是靠一代&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；又一代的人前赴后继才取得进步的，你们都是世界的功臣。所以，我认&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；为你们更值得拥有。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；世界上最奢侈的艺术品，是你独一无二的人生，&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；世界遗产诉说不尽的，是你的人生故事。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；amp；nbsp；所以，我愿意为你镌刻人生的点滴，我愿意为你诉说关于你的每段故事。&amp;amp；amp；amp；amp；quot；&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808034807448.png&quot; title=&quot;1528808034807448.png&quot; alt=&quot;img1-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808045104283.png&quot; title=&quot;1528808045104283.png&quot; alt=&quot;img2-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;br/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 'a:2:{i:0;s:6:\"精彩\";i:1;s:9:\"有干货\";}', '0', '1', '1', '2018-06-12 23:52:02', '2018-06-12 23:32:16');
INSERT INTO `edu_course` VALUES ('5', '4', '7', '芭蕾舞速成班', '6000.00', 'a:3:{i:0;s:62:\"http://educate.com/Upload/images/20180612/93560c2f50c0e132.jpg\";i:1;s:62:\"http://educate.com/Upload/images/20180612/3d65fff8d4a9c8ac.jpg\";i:2;s:62:\"http://educate.com/Upload/images/20180612/cd9daed46dad1b16.jpg\";}', 'http://47.95.208.179:3000/void/qianxun/video1.mp4', '每个人都是天生的舞者', '&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；quot；感恩先人开创的“茶马古道”，让我得以世界闻名，但从来，最好的我们&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；永远是宫廷贡品除了皇宫贵族就是赠送到国外。我认为，我应该属于更&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；多人，特别是没有背景，通过自己努力获得成功的人，人类，是靠一代&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；又一代的人前赴后继才取得进步的，你们都是世界的功臣。所以，我认&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；为你们更值得拥有。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；世界上最奢侈的艺术品，是你独一无二的人生，&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；世界遗产诉说不尽的，是你的人生故事。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；amp；nbsp；所以，我愿意为你镌刻人生的点滴，我愿意为你诉说关于你的每段故事。&amp;amp；amp；amp；quot；&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808034807448.png&quot; title=&quot;1528808034807448.png&quot; alt=&quot;img1-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808045104283.png&quot; title=&quot;1528808045104283.png&quot; alt=&quot;img2-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;br/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 'a:3:{i:0;s:6:\"漂亮\";i:1;s:6:\"精彩\";i:2;s:12:\"清新自然\";}', '0', '1', '1', '2018-06-12 23:52:11', '2018-06-12 23:38:51');
INSERT INTO `edu_course` VALUES ('6', '4', '6', '摄影理论基础', '688.00', 'a:3:{i:0;s:62:\"http://educate.com/Upload/images/20180612/f0b3798e73a5216c.jpg\";i:1;s:62:\"http://educate.com/Upload/images/20180612/49d6bb668c2bad8c.png\";i:2;s:62:\"http://educate.com/Upload/images/20180612/f18575f2b9e6b7ce.jpg\";}', 'http://47.95.208.179:3000/void/qianxun/video1.mp4', '世界不过是一张照片', '&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；quot；感恩先人开创的“茶马古道”，让我得以世界闻名，但从来，最好的我们&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；永远是宫廷贡品除了皇宫贵族就是赠送到国外。我认为，我应该属于更&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；多人，特别是没有背景，通过自己努力获得成功的人，人类，是靠一代&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；又一代的人前赴后继才取得进步的，你们都是世界的功臣。所以，我认&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；为你们更值得拥有。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；世界上最奢侈的艺术品，是你独一无二的人生，&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；世界遗产诉说不尽的，是你的人生故事。&lt;/span&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&amp;amp；amp；nbsp；所以，我愿意为你镌刻人生的点滴，我愿意为你诉说关于你的每段故事。&amp;amp；amp；quot；&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808034807448.png&quot; title=&quot;1528808034807448.png&quot; alt=&quot;img1-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;white-space: normal；&quot;&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;img src=&quot;http://educate.com/Upload/ueditor/Public/upload/ueditor/20180612/1528808045104283.png&quot; title=&quot;1528808045104283.png&quot; alt=&quot;img2-icon.png&quot;/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span class=&quot;inner-text-wrapper&quot;&gt;&lt;br/&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 'a:2:{i:0;s:12:\"直戳重点\";i:1;s:12:\"老师负责\";}', '0', '1', '1', '2018-06-12 23:52:00', '2018-06-12 23:51:50');

-- ----------------------------
-- Table structure for edu_course_tag
-- ----------------------------
DROP TABLE IF EXISTS `edu_course_tag`;
CREATE TABLE `edu_course_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '标签名称',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_course_tag
-- ----------------------------
INSERT INTO `edu_course_tag` VALUES ('1', '漂亮', '2018-06-05 16:11:36');
INSERT INTO `edu_course_tag` VALUES ('3', '高大上', '2018-06-05 10:16:55');
INSERT INTO `edu_course_tag` VALUES ('4', '精彩', '2018-06-05 16:11:10');
INSERT INTO `edu_course_tag` VALUES ('5', '有干货', '2018-06-06 06:13:36');
INSERT INTO `edu_course_tag` VALUES ('6', '老师负责', '2018-06-06 06:14:07');
INSERT INTO `edu_course_tag` VALUES ('7', '直戳重点', '2018-06-06 06:15:05');
INSERT INTO `edu_course_tag` VALUES ('8', '引人入胜', '2018-06-06 06:15:23');
INSERT INTO `edu_course_tag` VALUES ('9', '幽默风趣', '2018-06-06 06:15:54');
INSERT INTO `edu_course_tag` VALUES ('10', '清新自然', '2018-06-06 06:16:10');
INSERT INTO `edu_course_tag` VALUES ('11', '好', '2018-06-06 06:16:25');
INSERT INTO `edu_course_tag` VALUES ('12', '喜欢听', '2018-06-06 06:16:46');
INSERT INTO `edu_course_tag` VALUES ('13', '有魔力', '2018-06-06 06:17:00');

-- ----------------------------
-- Table structure for edu_course_type
-- ----------------------------
DROP TABLE IF EXISTS `edu_course_type`;
CREATE TABLE `edu_course_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `pic` varchar(255) NOT NULL COMMENT '图片',
  `sort_index` int(11) NOT NULL DEFAULT '1' COMMENT '排序序号',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='课程类型';

-- ----------------------------
-- Records of edu_course_type
-- ----------------------------
INSERT INTO `edu_course_type` VALUES ('1', '美术', 'http://educate.com/Upload/images/20180611/efd2a7bd352e0267.png', '1', '2018-06-12 23:40:50');
INSERT INTO `edu_course_type` VALUES ('2', '美食', 'http://educate.com/Upload/images/20180611/616843afdcd81e14.png', '2', '2018-06-12 23:46:54');
INSERT INTO `edu_course_type` VALUES ('3', '击剑', 'http://educate.com/Upload/images/20180611/e89413cf22236c57.png', '3', '2018-06-12 23:41:24');
INSERT INTO `edu_course_type` VALUES ('4', '乐器', 'http://educate.com/Upload/images/20180611/38d43048488b41d3.png', '4', '2018-06-12 23:41:38');
INSERT INTO `edu_course_type` VALUES ('5', '唱歌', 'http://educate.com/Upload/images/20180611/1785dce9d10b0b13.png', '5', '2018-06-12 23:41:58');
INSERT INTO `edu_course_type` VALUES ('6', '摄影', 'http://educate.com/Upload/images/20180611/4842c20713f3b350.png', '6', '2018-06-12 23:42:17');
INSERT INTO `edu_course_type` VALUES ('7', '运动', 'http://educate.com/Upload/images/20180611/19ba4d08053276e0.png', '7', '2018-06-12 23:47:02');
INSERT INTO `edu_course_type` VALUES ('8', '茶艺', 'http://educate.com/Upload/images/20180611/079ebdc59e23437d.png', '8', '2018-06-12 23:42:36');

-- ----------------------------
-- Table structure for edu_follow
-- ----------------------------
DROP TABLE IF EXISTS `edu_follow`;
CREATE TABLE `edu_follow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `follow_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 老师 2 课程',
  `relation_id` int(11) NOT NULL COMMENT '关联id',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_follow
-- ----------------------------

-- ----------------------------
-- Table structure for edu_opt_log
-- ----------------------------
DROP TABLE IF EXISTS `edu_opt_log`;
CREATE TABLE `edu_opt_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adminer` varchar(30) NOT NULL,
  `content` varchar(512) NOT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1登录后台 2 更新老师 3 更新课程 ',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1427 DEFAULT CHARSET=utf8 COMMENT='后台操作记录表';

-- ----------------------------
-- Records of edu_opt_log
-- ----------------------------
INSERT INTO `edu_opt_log` VALUES ('1343', '总管理员', '登录后台', '1', '2018-06-08 14:57:52');
INSERT INTO `edu_opt_log` VALUES ('1344', '总管理员', '登录后台', '1', '2018-06-08 15:00:54');
INSERT INTO `edu_opt_log` VALUES ('1345', '总管理员', '审核课程', '3', '2018-06-08 16:01:59');
INSERT INTO `edu_opt_log` VALUES ('1346', '总管理员', '编辑教师信息', '2', '2018-06-08 17:44:09');
INSERT INTO `edu_opt_log` VALUES ('1347', '总管理员', '更新FAQ', '1', '2018-06-08 17:45:25');
INSERT INTO `edu_opt_log` VALUES ('1348', '总管理员', '登录后台', '1', '2018-06-11 15:14:38');
INSERT INTO `edu_opt_log` VALUES ('1349', '总管理员', '编辑课程类型', '3', '2018-06-11 15:18:38');
INSERT INTO `edu_opt_log` VALUES ('1350', '总管理员', '编辑课程类型', '3', '2018-06-11 15:18:52');
INSERT INTO `edu_opt_log` VALUES ('1351', '总管理员', '编辑课程类型', '3', '2018-06-11 15:19:05');
INSERT INTO `edu_opt_log` VALUES ('1352', '总管理员', '编辑课程类型', '3', '2018-06-11 15:19:15');
INSERT INTO `edu_opt_log` VALUES ('1353', '总管理员', '编辑课程类型', '3', '2018-06-11 15:19:38');
INSERT INTO `edu_opt_log` VALUES ('1354', '总管理员', '编辑课程类型', '3', '2018-06-11 15:19:54');
INSERT INTO `edu_opt_log` VALUES ('1355', '总管理员', '编辑课程类型', '3', '2018-06-11 15:20:10');
INSERT INTO `edu_opt_log` VALUES ('1356', '总管理员', '编辑课程类型', '3', '2018-06-11 15:20:25');
INSERT INTO `edu_opt_log` VALUES ('1357', '总管理员', '更新Banner', '1', '2018-06-11 16:04:44');
INSERT INTO `edu_opt_log` VALUES ('1358', '总管理员', '更新Banner', '1', '2018-06-11 16:04:55');
INSERT INTO `edu_opt_log` VALUES ('1359', '总管理员', '更新Banner', '1', '2018-06-11 16:05:19');
INSERT INTO `edu_opt_log` VALUES ('1360', '总管理员', '更新Banner', '1', '2018-06-11 16:05:27');
INSERT INTO `edu_opt_log` VALUES ('1361', '总管理员', '更新Banner', '1', '2018-06-11 16:05:33');
INSERT INTO `edu_opt_log` VALUES ('1362', '总管理员', '更新Banner', '1', '2018-06-11 16:05:35');
INSERT INTO `edu_opt_log` VALUES ('1363', '总管理员', '登录后台', '1', '2018-06-12 19:55:51');
INSERT INTO `edu_opt_log` VALUES ('1364', '朱涛', '编辑课程信息', '3', '2018-06-12 19:58:36');
INSERT INTO `edu_opt_log` VALUES ('1365', '朱涛', '编辑课程信息', '3', '2018-06-12 20:02:14');
INSERT INTO `edu_opt_log` VALUES ('1366', '朱涛', '编辑课程信息', '3', '2018-06-12 20:05:12');
INSERT INTO `edu_opt_log` VALUES ('1367', '总管理员', '审核课程', '3', '2018-06-12 20:05:30');
INSERT INTO `edu_opt_log` VALUES ('1368', '总管理员', '审核课程', '3', '2018-06-12 20:05:33');
INSERT INTO `edu_opt_log` VALUES ('1369', '总管理员', '审核课程', '3', '2018-06-12 20:05:36');
INSERT INTO `edu_opt_log` VALUES ('1370', '总管理员', '设置推荐课程', '3', '2018-06-12 20:05:47');
INSERT INTO `edu_opt_log` VALUES ('1371', '总管理员', '设置推荐课程', '3', '2018-06-12 20:05:48');
INSERT INTO `edu_opt_log` VALUES ('1372', '总管理员', '设置推荐课程', '3', '2018-06-12 20:05:50');
INSERT INTO `edu_opt_log` VALUES ('1373', '总管理员', '设置推荐课程', '3', '2018-06-12 20:05:51');
INSERT INTO `edu_opt_log` VALUES ('1374', '总管理员', '设置推荐课程', '3', '2018-06-12 20:34:57');
INSERT INTO `edu_opt_log` VALUES ('1375', '朱涛', '编辑课程信息', '3', '2018-06-12 20:54:08');
INSERT INTO `edu_opt_log` VALUES ('1376', '朱涛', '编辑课程信息', '3', '2018-06-12 20:54:41');
INSERT INTO `edu_opt_log` VALUES ('1377', '朱涛', '编辑课程信息', '3', '2018-06-12 20:54:55');
INSERT INTO `edu_opt_log` VALUES ('1378', '朱涛', '编辑课程信息', '3', '2018-06-12 20:55:08');
INSERT INTO `edu_opt_log` VALUES ('1379', '总管理员', '审核课程', '3', '2018-06-12 20:55:30');
INSERT INTO `edu_opt_log` VALUES ('1380', '总管理员', '审核课程', '3', '2018-06-12 20:55:33');
INSERT INTO `edu_opt_log` VALUES ('1381', '总管理员', '审核课程', '3', '2018-06-12 20:55:35');
INSERT INTO `edu_opt_log` VALUES ('1382', '总管理员', '设置推荐教师', '2', '2018-06-12 20:58:47');
INSERT INTO `edu_opt_log` VALUES ('1383', '总管理员', '设置推荐教师', '2', '2018-06-12 20:59:08');
INSERT INTO `edu_opt_log` VALUES ('1384', '总管理员', '设置推荐教师', '2', '2018-06-12 20:59:17');
INSERT INTO `edu_opt_log` VALUES ('1385', '总管理员', '设置推荐教师', '2', '2018-06-12 20:59:21');
INSERT INTO `edu_opt_log` VALUES ('1386', '朱涛', '编辑课程信息', '3', '2018-06-12 23:32:16');
INSERT INTO `edu_opt_log` VALUES ('1387', '朱涛', '编辑课程信息', '3', '2018-06-12 23:32:36');
INSERT INTO `edu_opt_log` VALUES ('1388', '朱涛', '编辑课程信息', '3', '2018-06-12 23:35:04');
INSERT INTO `edu_opt_log` VALUES ('1389', '朱涛', '编辑课程信息', '3', '2018-06-12 23:38:51');
INSERT INTO `edu_opt_log` VALUES ('1390', '总管理员', '编辑课程类型', '3', '2018-06-12 23:40:50');
INSERT INTO `edu_opt_log` VALUES ('1391', '总管理员', '编辑课程类型', '3', '2018-06-12 23:41:02');
INSERT INTO `edu_opt_log` VALUES ('1392', '总管理员', '编辑课程类型', '3', '2018-06-12 23:41:24');
INSERT INTO `edu_opt_log` VALUES ('1393', '总管理员', '编辑课程类型', '3', '2018-06-12 23:41:38');
INSERT INTO `edu_opt_log` VALUES ('1394', '总管理员', '编辑课程类型', '3', '2018-06-12 23:41:58');
INSERT INTO `edu_opt_log` VALUES ('1395', '总管理员', '编辑课程类型', '3', '2018-06-12 23:42:17');
INSERT INTO `edu_opt_log` VALUES ('1396', '总管理员', '编辑课程类型', '3', '2018-06-12 23:42:36');
INSERT INTO `edu_opt_log` VALUES ('1397', '总管理员', '编辑课程类型', '3', '2018-06-12 23:42:50');
INSERT INTO `edu_opt_log` VALUES ('1398', '总管理员', '编辑课程类型', '3', '2018-06-12 23:43:01');
INSERT INTO `edu_opt_log` VALUES ('1399', '朱涛', '编辑课程信息', '3', '2018-06-12 23:44:47');
INSERT INTO `edu_opt_log` VALUES ('1400', '朱涛', '编辑课程信息', '3', '2018-06-12 23:45:22');
INSERT INTO `edu_opt_log` VALUES ('1401', '朱涛', '编辑课程信息', '3', '2018-06-12 23:45:43');
INSERT INTO `edu_opt_log` VALUES ('1402', '总管理员', '编辑课程类型', '3', '2018-06-12 23:46:54');
INSERT INTO `edu_opt_log` VALUES ('1403', '总管理员', '编辑课程类型', '3', '2018-06-12 23:47:02');
INSERT INTO `edu_opt_log` VALUES ('1404', '朱涛', '编辑课程信息', '3', '2018-06-12 23:47:34');
INSERT INTO `edu_opt_log` VALUES ('1405', '朱涛', '编辑课程信息', '3', '2018-06-12 23:47:49');
INSERT INTO `edu_opt_log` VALUES ('1406', '朱涛', '编辑课程信息', '3', '2018-06-12 23:51:50');
INSERT INTO `edu_opt_log` VALUES ('1407', '总管理员', '审核课程', '3', '2018-06-12 23:52:00');
INSERT INTO `edu_opt_log` VALUES ('1408', '总管理员', '审核课程', '3', '2018-06-12 23:52:02');
INSERT INTO `edu_opt_log` VALUES ('1409', '总管理员', '审核课程', '3', '2018-06-12 23:52:04');
INSERT INTO `edu_opt_log` VALUES ('1410', '总管理员', '审核课程', '3', '2018-06-12 23:52:07');
INSERT INTO `edu_opt_log` VALUES ('1411', '总管理员', '审核课程', '3', '2018-06-12 23:52:09');
INSERT INTO `edu_opt_log` VALUES ('1412', '总管理员', '审核课程', '3', '2018-06-12 23:52:11');
INSERT INTO `edu_opt_log` VALUES ('1413', '总管理员', '设置推荐课程', '3', '2018-06-12 23:54:14');
INSERT INTO `edu_opt_log` VALUES ('1414', '总管理员', '设置推荐课程', '3', '2018-06-12 23:54:16');
INSERT INTO `edu_opt_log` VALUES ('1415', '总管理员', '设置推荐课程', '3', '2018-06-12 23:54:17');
INSERT INTO `edu_opt_log` VALUES ('1416', '总管理员', '设置推荐课程', '3', '2018-06-12 23:54:19');
INSERT INTO `edu_opt_log` VALUES ('1417', '总管理员', '设置推荐课程', '3', '2018-06-12 23:54:21');
INSERT INTO `edu_opt_log` VALUES ('1418', '总管理员', '登录后台', '1', '2018-06-13 14:37:00');
INSERT INTO `edu_opt_log` VALUES ('1419', '总管理员', '设置推荐课程', '3', '2018-06-13 15:55:23');
INSERT INTO `edu_opt_log` VALUES ('1420', '总管理员', '设置推荐课程', '3', '2018-06-13 15:55:24');
INSERT INTO `edu_opt_log` VALUES ('1421', '总管理员', '登录后台', '1', '2018-06-14 00:33:25');
INSERT INTO `edu_opt_log` VALUES ('1422', '朱涛', '编辑课程信息', '3', '2018-06-14 00:36:18');
INSERT INTO `edu_opt_log` VALUES ('1423', '朱涛', '编辑课程信息', '3', '2018-06-14 00:36:40');
INSERT INTO `edu_opt_log` VALUES ('1424', '总管理员', '审核课程', '3', '2018-06-14 00:36:59');
INSERT INTO `edu_opt_log` VALUES ('1425', '总管理员', '审核课程', '3', '2018-06-14 00:37:01');
INSERT INTO `edu_opt_log` VALUES ('1426', '总管理员', '登录后台', '1', '2018-06-14 10:33:58');

-- ----------------------------
-- Table structure for edu_order
-- ----------------------------
DROP TABLE IF EXISTS `edu_order`;
CREATE TABLE `edu_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 普通订单 2 预约订单',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `teacher_id` int(11) NOT NULL COMMENT '老师ID',
  `course_id` int(11) NOT NULL COMMENT '课程ID',
  `goods_num` int(11) NOT NULL DEFAULT '1' COMMENT '物品数量',
  `order_sn` varchar(32) NOT NULL COMMENT '订单号',
  `pay_money` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '用户付的金额',
  `price` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `name` varchar(30) NOT NULL COMMENT '姓名',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1、待付款  2 、已付款/预约中/待评价 3、已评价 4、已取消',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `comment_time` datetime DEFAULT NULL COMMENT '评论时间',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_order
-- ----------------------------
INSERT INTO `edu_order` VALUES ('1', '1', '3', '4', '1', '1', '201806072011', '988.00', '988.00', '神涛', '15862313825', '1', null, null, '2018-06-07 11:12:25');
INSERT INTO `edu_order` VALUES ('2', '1', '3', '4', '1', '1', '201806072012', '0.00', '988.00', '神涛', '15862313825', '2', null, null, '2018-06-04 15:35:14');
INSERT INTO `edu_order` VALUES ('3', '1', '3', '4', '1', '1', '201806072013', '0.00', '988.00', '神涛', '15862313825', '3', null, null, '2018-05-29 15:06:52');
INSERT INTO `edu_order` VALUES ('4', '2', '3', '4', '1', '1', '201806072013', '0.00', '988.00', '神涛', '15862313825', '1', null, null, '2018-05-29 15:06:52');
INSERT INTO `edu_order` VALUES ('5', '1', '19', '4', '1', '1', '201806140945476452', '0.00', '988.00', '朱涛', '15862313825', '2', null, null, '2018-06-14 09:45:47');
INSERT INTO `edu_order` VALUES ('7', '1', '19', '4', '1', '1', '201806140949545188', '988.00', '988.00', '朱涛', '15862313825', '1', '2018-06-14 09:49:54', null, '2018-06-14 09:49:54');
INSERT INTO `edu_order` VALUES ('8', '1', '19', '4', '2', '2', '201806141036332395', '3000.00', '3000.00', '朱涛', '15862313825', '1', '2018-06-14 10:36:33', null, '2018-06-14 10:36:33');

-- ----------------------------
-- Table structure for edu_system
-- ----------------------------
DROP TABLE IF EXISTS `edu_system`;
CREATE TABLE `edu_system` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 faq 2 关于我们',
  `content` text NOT NULL COMMENT '内容',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='系统信息表/关于我们';

-- ----------------------------
-- Records of edu_system
-- ----------------------------
INSERT INTO `edu_system` VALUES ('1', '1', '&lt;p&gt;A、你是帅哥吗？&lt;br/&gt;Q、我不是？&lt;/p&gt;&lt;p&gt;A、你是说我没有眼光咯？&lt;br/&gt;Q、我是帅哥！&lt;br/&gt;A、一点儿也不谦虚！&lt;/p&gt;&lt;p&gt;Q、那我该说什么呢&lt;/p&gt;', '2018-06-08 17:45:25');
INSERT INTO `edu_system` VALUES ('2', '2', '&lt;p&gt;我们是共产主义接班人&lt;/p&gt;', '2018-06-06 17:00:12');

-- ----------------------------
-- Table structure for edu_teacher
-- ----------------------------
DROP TABLE IF EXISTS `edu_teacher`;
CREATE TABLE `edu_teacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `teacher_type` int(11) NOT NULL COMMENT '老师类型ID',
  `teach_type` int(11) NOT NULL COMMENT '授课类型ID',
  `nickname` varchar(30) NOT NULL COMMENT '老师昵称',
  `name` varchar(30) NOT NULL COMMENT '老师姓名',
  `headimgurl` varchar(500) NOT NULL COMMENT '老师头像',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 男 2 女',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `links` text NOT NULL,
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `profile` text NOT NULL COMMENT '老师简介',
  `content` text NOT NULL COMMENT '教师详情',
  `lng` float(11,6) NOT NULL DEFAULT '0.000000' COMMENT '经度',
  `lat` float(11,6) NOT NULL DEFAULT '0.000000' COMMENT '纬度',
  `address` varchar(255) NOT NULL COMMENT '地理位置',
  `fcard` varchar(255) NOT NULL COMMENT '身份证正面',
  `bcard` varchar(255) NOT NULL COMMENT '身份证反面',
  `qualification` varchar(255) NOT NULL COMMENT '资格证书',
  `is_stick` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1.推荐 2、不推荐',
  `status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '1、审核通过 2、审核不通过 3、审核中 4、注销',
  `check_time` datetime DEFAULT NULL COMMENT '审核时间',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_teacher
-- ----------------------------
INSERT INTO `edu_teacher` VALUES ('1', '4', '1', '1', '飞翔的岁月', '星空', 'http://educate.com/Upload/images/20180605/27675ff12e1e74c9.jpg', '1', '15262997833', 'a:0:{}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '113.864288', '34.517559', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '1', '1', '2018-05-24 09:13:18', '2018-05-24 14:09:17');
INSERT INTO `edu_teacher` VALUES ('2', '1', '3', '1', '飞翔的岁月', '阿涛', 'http://educate.com/Upload/images/20180605/1f413ba123252221.jpg', '1', '15262997833', 'a:3:{i:0;s:20:\"http://www.baidu.com\";i:1;s:20:\"http://www.baidu.com\";i:2;s:20:\"http://www.baidu.com\";}', '66666@qq.com', '他挺好的', '&lt;p&gt;老师挺帅的&lt;img src=&quot;http://img.baidu.com/hi/jx2/j_0059.gif&quot;/&gt;&lt;/p&gt;', '116.222023', '39.913830', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '1', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:46');
INSERT INTO `edu_teacher` VALUES ('3', '0', '7', '2', '飞翔的岁月', '莲心', 'http://educate.com/Upload/images/20180605/7ffa1325c76fd81c.gif', '2', '15262997833', 'a:2:{i:0;s:20:\"http://www.baidu.com\";i:1;s:20:\"http://www.baidu.com\";}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '113.864288', '34.517559', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '1', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:30');
INSERT INTO `edu_teacher` VALUES ('4', '2', '1', '1', '飞翔的岁月', '朱涛', 'http://educate.com/Upload/images/20180605/ed11ed37ded6d5c2.jpg', '1', '15262997833', 'a:3:{i:0;s:20:\"http://www.baidu.com\";i:1;s:20:\"http://www.baidu.com\";i:2;s:20:\"http://www.baidu.com\";}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '116.621010', '39.981533', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '1', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:50');
INSERT INTO `edu_teacher` VALUES ('5', '0', '7', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('6', '0', '1', '1', '飞翔的岁月', '天荒', 'http://educate.com/Upload/images/20180605/117acc7832a74434.jpg', '1', '15262997833', 'a:0:{}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '120.800072', '30.862574', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:31');
INSERT INTO `edu_teacher` VALUES ('7', '0', '3', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('8', '0', '1', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('9', '0', '1', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '4', '2018-05-24 09:13:18', '2018-05-24 15:51:08');
INSERT INTO `edu_teacher` VALUES ('10', '0', '7', '2', '飞翔的岁月', '英纯', 'http://educate.com/Upload/images/20180605/9d38f843cfbb0fb8.gif', '2', '15262997833', 'a:2:{i:1;s:20:\"http://www.baidu.com\";i:2;s:20:\"http://www.baidu.com\";}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '1', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:33');
INSERT INTO `edu_teacher` VALUES ('11', '0', '3', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('12', '0', '7', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('13', '0', '3', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('14', '0', '1', '2', '飞翔的岁月', '文君', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '2', '15262997833', 'a:1:{i:1;s:20:\"http://www.baidu.com\";}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '116.540527', '39.950123', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:36');
INSERT INTO `edu_teacher` VALUES ('15', '0', '7', '1', '飞翔的岁月', '江山', 'http://educate.com/Upload/images/20180605/021b24ede5896337.jpg', '2', '15262997833', 'a:5:{i:0;s:20:\"http://www.baidu.com\";i:1;s:20:\"http://www.baidu.com\";i:2;s:20:\"http://www.baidu.com\";i:3;s:20:\"http://www.baidu.com\";i:4;s:20:\"http://www.baidu.com\";}', '418695877@qq.com', '聪明活泼，积极向上', '&lt;p&gt;一个字，漂亮&lt;br/&gt;&lt;/p&gt;', '116.540527', '39.950123', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-26 09:21:41');
INSERT INTO `edu_teacher` VALUES ('16', '0', '1', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '4', '2018-05-24 09:13:18', '2018-05-24 11:37:41');
INSERT INTO `edu_teacher` VALUES ('17', '0', '1', '2', '飞翔的岁月', '紫宸', 'http://educate.com/Upload/images/20180605/3d8a5af413457e90.gif', '1', '15262997833', 'a:3:{i:1;s:20:\"http://www.baidu.com\";i:2;s:20:\"http://www.baidu.com\";i:3;s:20:\"http://www.baidu.com\";}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 16:06:10');
INSERT INTO `edu_teacher` VALUES ('18', '0', '7', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '2', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '2', '2018-05-24 09:13:18', '2018-05-26 09:22:03');
INSERT INTO `edu_teacher` VALUES ('19', '0', '1', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '2', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '2', '2018-05-24 09:13:18', '2018-05-24 16:06:08');
INSERT INTO `edu_teacher` VALUES ('20', '0', '3', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:07');
INSERT INTO `edu_teacher` VALUES ('21', '0', '7', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:07');
INSERT INTO `edu_teacher` VALUES ('22', '0', '3', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:07');
INSERT INTO `edu_teacher` VALUES ('23', '0', '7', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '2', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:07');
INSERT INTO `edu_teacher` VALUES ('24', '0', '1', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '2', '2018-05-24 09:13:18', '2018-05-24 17:30:02');
INSERT INTO `edu_teacher` VALUES ('25', '0', '1', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:06');
INSERT INTO `edu_teacher` VALUES ('26', '0', '3', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:06');
INSERT INTO `edu_teacher` VALUES ('27', '0', '1', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '2', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:06');
INSERT INTO `edu_teacher` VALUES ('28', '0', '7', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:05');
INSERT INTO `edu_teacher` VALUES ('29', '0', '1', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:05');
INSERT INTO `edu_teacher` VALUES ('30', '0', '7', '2', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '2', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:05');
INSERT INTO `edu_teacher` VALUES ('31', '0', '3', '1', '飞翔的岁月', '朱涛', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '1', '15262997833', '', '418695877@qq.com', '聪明活泼，积极向上', '', '0.000000', '0.000000', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '3', '2018-05-24 09:13:18', '2018-05-24 16:06:05');
INSERT INTO `edu_teacher` VALUES ('32', '0', '1', '1', '飞翔的岁月', '秦科', 'http://educate.com/Upload/images/20180605/38c7a36d0f3af488.jpg', '1', '15262997833', 'a:0:{}', '418695877@qq.com', '&lt;p&gt;聪明活泼，积极向上&lt;/p&gt;', '', '113.864288', '34.517559', '苏州工业园区若水路388号纳米科技园E栋1206楼', '', '', '', '2', '1', '2018-05-24 09:13:18', '2018-05-24 14:09:17');

-- ----------------------------
-- Table structure for edu_teach_type
-- ----------------------------
DROP TABLE IF EXISTS `edu_teach_type`;
CREATE TABLE `edu_teach_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `sort_index` int(11) NOT NULL DEFAULT '1' COMMENT '排序序号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 显示 2 删除',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_teach_type
-- ----------------------------
INSERT INTO `edu_teach_type` VALUES ('1', '数学', '1', '1', '2018-05-24 08:24:08');
INSERT INTO `edu_teach_type` VALUES ('2', '英语', '2', '1', '2018-05-24 08:53:50');
INSERT INTO `edu_teach_type` VALUES ('3', '自然', '3', '2', '2018-06-05 10:16:55');

-- ----------------------------
-- Table structure for edu_user
-- ----------------------------
DROP TABLE IF EXISTS `edu_user`;
CREATE TABLE `edu_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(32) NOT NULL COMMENT '微信openid',
  `nickname` varchar(30) NOT NULL COMMENT '微信昵称',
  `headimgurl` varchar(500) NOT NULL COMMENT '微信头像',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未知 1 男 2 女',
  `name` varchar(30) NOT NULL COMMENT '真实姓名',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `country` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `lng` float(11,5) NOT NULL DEFAULT '0.00000' COMMENT '经度',
  `lat` float(11,5) NOT NULL DEFAULT '0.00000' COMMENT '纬度',
  `identify` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 普通会员 2 老师  ',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of edu_user
-- ----------------------------
INSERT INTO `edu_user` VALUES ('1', 'a', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '阿涛', '15862313825', '', '', '', '0.00000', '0.00000', '2', '2018-06-07 11:45:48');
INSERT INTO `edu_user` VALUES ('2', 'b', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '朱涛', '15862313825', '', '', '', '0.00000', '0.00000', '2', '2018-06-07 11:45:44');
INSERT INTO `edu_user` VALUES ('3', 'c', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '神涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-07 11:04:39');
INSERT INTO `edu_user` VALUES ('4', 'd', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '星空', '15862313825', '', '', '', '0.00000', '0.00000', '2', '2018-06-07 11:45:35');
INSERT INTO `edu_user` VALUES ('5', 'd', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '星空', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-05-29 08:59:01');
INSERT INTO `edu_user` VALUES ('6', 'c', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '神涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-07 11:04:39');
INSERT INTO `edu_user` VALUES ('7', 'a', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '阿涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-04 08:58:57');
INSERT INTO `edu_user` VALUES ('8', 'b', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '朱涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-04 11:45:44');
INSERT INTO `edu_user` VALUES ('9', 'd', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '星空', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-05 08:59:01');
INSERT INTO `edu_user` VALUES ('10', 'c', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '神涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-05 11:04:39');
INSERT INTO `edu_user` VALUES ('11', 'b', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '朱涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-05-29 11:45:44');
INSERT INTO `edu_user` VALUES ('12', 'a', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '阿涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-05-28 08:58:57');
INSERT INTO `edu_user` VALUES ('13', 'd', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '星空', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-08 08:59:01');
INSERT INTO `edu_user` VALUES ('14', 'c', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '神涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-07 11:04:39');
INSERT INTO `edu_user` VALUES ('15', 'b', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '朱涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-07 11:45:44');
INSERT INTO `edu_user` VALUES ('16', 'a', '飞翔的岁月', 'http://wx.qlogo.cn/mmopen/PiajxSqBRaELdhvu2Ut9r1DIxLEZHowic6Itkpov8mvU7JJpk9oriaOFI9RKo1xrAwibaKIjjabylp2hANzr8xeS7g/0', '0', '阿涛', '15862313825', '', '', '', '0.00000', '0.00000', '1', '2018-06-08 08:58:57');
INSERT INTO `edu_user` VALUES ('19', 'oiGOe4sbohUGrN_3Ygy9YFv9lDuY', '飞翔的岁月', 'https://wx.qlogo.cn/mmopen/vi_32/cD25Wkl5OZGo9nb3R131SFXv27hTviabJEWiabBTSSvytVm685K7jhFUibGevsibVSsBFbk6ib9lZZ84aibfdPCIqJAw/132', '1', '', '15862313825', 'China', 'Jiangsu', 'Suzhou', '0.00000', '0.00000', '1', '2018-06-14 09:15:09');
