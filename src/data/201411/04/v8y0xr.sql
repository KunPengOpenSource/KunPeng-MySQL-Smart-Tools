-- filename=2014-11-02_18-23-24-xscj-ta_Class.sql
DROP TABLE IF EXISTS `ta_Class`;

-- ta_Class
CREATE TABLE `ta_Class` (
  `cla_id` int(11) NOT NULL,
  `cla_name` varchar(30) NOT NULL,
  `cla_count` tinyint(3) NOT NULL,
  `cla_date` int(4) NOT NULL,
  PRIMARY KEY (`cla_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ta_Class` VALUES ( '20102102', '????????2?', '32', '2010');
INSERT INTO `ta_Class` VALUES ( '20112001', '????1?', '48', '2011');
INSERT INTO `ta_Class` VALUES ( '20112002', '????2?', '49', '2011');
INSERT INTO `ta_Class` VALUES ( '20112003', '???1?', '52', '2011');
INSERT INTO `ta_Class` VALUES ( '20112101', '????????1?', '31', '2011');
INSERT INTO `ta_Class` VALUES ( '20112102', '????????2?', '30', '2011');
INSERT INTO `ta_Class` VALUES ( '20122001', '????1?', '55', '2012');
INSERT INTO `ta_Class` VALUES ( '20122002', '????2?', '58', '2012');
INSERT INTO `ta_Class` VALUES ( '20122003', '???1?', '53', '2012');
INSERT INTO `ta_Class` VALUES ( '20122101', '????????1?', '29', '2012');
INSERT INTO `ta_Class` VALUES ( '20122102', '????????2?', '33', '2012');


