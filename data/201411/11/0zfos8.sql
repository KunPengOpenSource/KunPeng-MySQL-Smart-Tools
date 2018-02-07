-- filename=2014-11-11_19-14-38-cartdb-goods.sql
DROP TABLE IF EXISTS `goods`;

-- goods
CREATE TABLE `goods` (
  `goods_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(100) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `price` float NOT NULL DEFAULT '199',
  `num` bigint(20) NOT NULL DEFAULT '0',
  `time` datetime DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `goods` VALUES ( '2', 'phone', '6.jpg', '1998', '9', '2010-10-01 00:00:00', ' This is a phone');
INSERT INTO `goods` VALUES ( '3', 'vedio', '7.jpg', '700', '21', '2008-08-08 08:08:00', ' This is a yinxiang');
INSERT INTO `goods` VALUES ( '4', 'ear', '8.jpg', '179', '2', '2013-01-01 00:00:00', ' This is a erji');


