# Host: 211.69.33.220  (Version: 5.5.38-0ubuntu0.12.04.1-log)
# Date: 2014-09-11 13:54:37
# Generator: MySQL-Front 5.3  (Build 4.56)

/*!40101 SET NAMES utf8 */;

CREATE EVENT `mysql_bytes_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_bytes
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_connections_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_connections
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_innodb_buffer_hit_rate_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_innodb_buffer_hit_rate
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_key_buffer_hit_rate_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_key_buffer_hit_rate
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_qps_tps_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_qps_tps
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;

CREATE EVENT `mysql_query_cache_hit_rate_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_query_cache_hit_rate
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_query_throughput_rate_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_query_throughput_rate
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_thread_cache_hit_rate_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_thread_cache_hit_rate
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
 
CREATE EVENT `mysql_threads_delete` 
ON SCHEDULE EVERY 1 DAY 
DO delete from mysql_threads
where unix_timestamp(now())-unix_timestamp(date_format(create_time,'%Y-%m-%d'))>90*24*360;
