-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-12-01 13:31:59
-- 服务器版本: 5.5.38-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `cartdb`
--

-- --------------------------------------------------------

--
-- 表的结构 `catalog`
--

CREATE TABLE IF NOT EXISTS `catalog` (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
  `cid` mediumint(6) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `catalog`
--

INSERT INTO `catalog` (`id`, `cid`, `title`) VALUES
(1, 1, 'PHP'),
(2, 1, 'JAVA'),
(3, 1, 'C++'),
(4, 1, 'ASP.NET'),
(5, 2, 'JS'),
(6, 2, 'JQUERY'),
(7, 2, 'HTML'),
(8, 2, 'CSS'),
(9, 3, 'MySQL'),
(10, 3, 'ORACLE'),
(11, 3, 'SQL');

-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE IF NOT EXISTS `goods` (
  `goods_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(100) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `price` float NOT NULL DEFAULT '199',
  `num` bigint(20) NOT NULL DEFAULT '0',
  `time` datetime DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `goods`
--

INSERT INTO `goods` (`goods_id`, `goods_name`, `pic`, `price`, `num`, `time`, `note`) VALUES
(1, 'phone', '6.jpg', 1998, 9, '2010-10-01 00:00:00', ' This is a phone'),
(2, 'nn', '1.jpg', 123, 1, '0000-00-00 00:00:00', 'eee'),
(3, 'vedio', '7.jpg', 700, 21, '2008-08-08 08:08:00', ' This is a yinxiang'),
(4, 'ear', '8.jpg', 179, 2, '2013-01-01 00:00:00', ' This is a erji');

-- --------------------------------------------------------

--
-- 替换视图以便查看 `new_views`
--
CREATE TABLE IF NOT EXISTS `new_views` (
`id` mediumint(6)
,`cid` mediumint(6)
,`title` varchar(50)
);
-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(64) DEFAULT NULL,
  `buy_number` int(11) DEFAULT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`goods_id`, `goods_name`, `buy_number`) VALUES
(2, 'phone', 1);

--
-- 触发器 `order`
--
DROP TRIGGER IF EXISTS `tg1`;
DELIMITER //
CREATE TRIGGER `tg1` AFTER INSERT ON `order`
 FOR EACH ROW update goods set num=num-new.buy_number where goods_id=new.goods_id
//
DELIMITER ;
DROP TRIGGER IF EXISTS `tg2`;
DELIMITER //
CREATE TRIGGER `tg2` AFTER DELETE ON `order`
 FOR EACH ROW update goods set num = num+old.buy_number where goods_id = old.goods_id
//
DELIMITER ;
DROP TRIGGER IF EXISTS `tg3`;
DELIMITER //
CREATE TRIGGER `tg3` AFTER UPDATE ON `order`
 FOR EACH ROW update goods set num = num+old.buy_number-new.buy_number where goods_id=new.goods_id
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 视图结构 `new_views`
--
DROP TABLE IF EXISTS `new_views`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `new_views` AS select `catalog`.`id` AS `id`,`catalog`.`cid` AS `cid`,`catalog`.`title` AS `title` from `catalog` WITH CASCADED CHECK OPTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
