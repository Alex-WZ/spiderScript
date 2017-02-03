CREATE DATABASE IF NOT EXISTS database DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

CREATE TABLE `zhihu_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txt` text COMMENT '回答的内容',
  `proCount` varchar(32) DEFAULT NULL COMMENT '这条回答的赞同数',
  `commentId` varchar(32) DEFAULT NULL COMMENT '这条回答的评论id',
  `commentCount` varchar(32) DEFAULT NULL COMMENT '这条回答的评论数',
  `commentHtml` text COMMENT '这条回答的源码',
  `questionID` int(11) DEFAULT NULL COMMENT '问题的id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
