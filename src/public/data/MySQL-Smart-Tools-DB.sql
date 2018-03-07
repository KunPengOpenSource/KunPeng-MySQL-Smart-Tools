# Host: 211.69.33.220  (Version: 5.5.38-0ubuntu0.12.04.1-log)
# Date: 2014-09-01 19:44:41
# Generator: MySQL-Front 5.3  (Build 4.56)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "GLOBAL_VARIABLES"
#

DROP TABLE IF EXISTS `GLOBAL_VARIABLES`;
CREATE TABLE `GLOBAL_VARIABLES` (
  `VARIABLE_NAME` varchar(64) NOT NULL DEFAULT '',
  `VARIABLE_VALUE` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for table "mysql_server"
#

DROP TABLE IF EXISTS `mysql_server`;
CREATE TABLE `mysql_server` (
  `hostid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主机编号',
  `mhost` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '主机地址',
  `mport` int(11) NOT NULL DEFAULT '3306' COMMENT '服务器端口',
  `muser` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT 'MySQL用户名',
  `mpwd` varchar(45) CHARACTER SET utf8 NOT NULL COMMENT 'MySQL密码',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`hostid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_query_throughput_rate"
#

DROP TABLE IF EXISTS `mysql_query_throughput_rate`;
CREATE TABLE `mysql_query_throughput_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `Changedb` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '数据库改变次数',
  `Com_select` varchar(255) NOT NULL DEFAULT '' COMMENT '查找次数',
  `Com_insert` varchar(255) NOT NULL DEFAULT '' COMMENT '插入次数',
  `Com_update` varchar(255) NOT NULL DEFAULT '' COMMENT '更新次数',
  `Com_delete` varchar(255) NOT NULL DEFAULT '' COMMENT '删除次数',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_query_throughput_rate_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_query_throughput_rate_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8858 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_query_cache_hit_rate"
#

DROP TABLE IF EXISTS `mysql_query_cache_hit_rate`;
CREATE TABLE `mysql_query_cache_hit_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `Qcache_hits` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '缓存命中次数',
  `Qcache_inserts` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '缓存失效次数',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_query_cache_ hit_rate_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_query_cache_ hit_rate_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8859 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_qps_tps"
#

DROP TABLE IF EXISTS `mysql_qps_tps`;
CREATE TABLE `mysql_qps_tps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `QPS` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '每秒查询量',
  `TPS` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '每秒事务量',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_qps_tps_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_qps_tps_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=333220 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_process"
#

DROP TABLE IF EXISTS `mysql_process`;
CREATE TABLE `mysql_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `db` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '数据库',
  `command` varchar(200) CHARACTER SET utf8 NOT NULL COMMENT '命令',
  `time` datetime NOT NULL COMMENT '时间',
  `state` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '状态',
  `info` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '信息',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_process_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_process_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_key_buffer_hit_rate"
#

DROP TABLE IF EXISTS `mysql_key_buffer_hit_rate`;
CREATE TABLE `mysql_key_buffer_hit_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `Key_reads` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '从硬盘读取键的数据块的次数',
  `Key_read_requests` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '从缓存读键的数据块的请求数',
  `Key_writes` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '向硬盘写入将键的数据块的物理写操作的次数',
  `Key_write_requests` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '将键的数据块写入缓存的请求数',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_key_buffer_hit_rate_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_key_buffer_hit_rate_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=333220 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_innodb_buffer_hit_rate"
#

DROP TABLE IF EXISTS `mysql_innodb_buffer_hit_rate`;
CREATE TABLE `mysql_innodb_buffer_hit_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `Innodb_buf_reads` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '不满足InnoDB必须单页读取的缓冲池中的逻辑读数量',
  `Innodb_buf_read_req` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'InnoDB已经完成的逻辑读请求数',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_innodb_buffer_hit_rate_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_innodb_buffer_hit_rate_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=333220 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_connections"
#

DROP TABLE IF EXISTS `mysql_connections`;
CREATE TABLE `mysql_connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `hostid` int(11) NOT NULL,
  `Max_connections` varchar(255) NOT NULL COMMENT '允许最大连接数',
  `Max_used_connections` varchar(255) NOT NULL COMMENT '过去最大连接数',
  `Threads_connections` varchar(255) NOT NULL COMMENT '当前连接数',
  `Threads_running` varchar(255) NOT NULL COMMENT '活动连接数',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `fk_mysql_connections_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_connections_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=326000 DEFAULT CHARSET=utf8;

#
# Structure for table "mysql_cnf"
#

DROP TABLE IF EXISTS `mysql_cnf`;
CREATE TABLE `mysql_cnf` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `hostid` int(11) NOT NULL,
  `variable` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '变量名',
  `value` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '变量值',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `fk_mysql_cnf_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_cnf_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_bytes"
#

DROP TABLE IF EXISTS `mysql_bytes`;
CREATE TABLE `mysql_bytes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `Bytes_sent` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '发送字节',
  `Bytes_received` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '接收字节',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_bytes_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_bytes_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=330729 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_thread_cache_hit_rate"
#

DROP TABLE IF EXISTS `mysql_thread_cache_hit_rate`;
CREATE TABLE `mysql_thread_cache_hit_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `threads_created` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '连接过的进程数',
  `connections` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '总连接数',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_thread_cache_hit_rate_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_thread_cache_hit_rate_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12885 DEFAULT CHARSET=latin1;

#
# Structure for table "mysql_threads"
#

DROP TABLE IF EXISTS `mysql_threads`;
CREATE TABLE `mysql_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `Threads_created` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '每秒创建线程数',
  `Threads_connected` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '当前连接数',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `hostid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mysql_threads_mysql_server1_idx` (`hostid`),
  CONSTRAINT `fk_mysql_threads_mysql_server1` FOREIGN KEY (`hostid`) REFERENCES `mysql_server` (`hostid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8923 DEFAULT CHARSET=latin1;

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `username` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT 'admin' COMMENT '用户名',
  `userpwd` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT 'admin' COMMENT '用户密码',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
