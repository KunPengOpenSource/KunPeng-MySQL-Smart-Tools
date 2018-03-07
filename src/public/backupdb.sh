#!/bin/bash

#1.数据库信息定�? 
mysql_host="localhost"  
mysql_user="root"  
mysql_passwd="MySQLSmartTools"  
  
#sql备份目录  
root_dir="/var/www/html/MSTV2.0/public/data/backup"  
back_dir="/var/www/html/MSTV2.0/public/data/backup/databases"  
data_dir="databases"  
store_dir="database"  
if [ ! -d $back_dir ]; then  
    mkdir -p $back_dir  
fi  
  
#备份的数据库数组  
db_arr=$(echo "show databases;" | mysql -u$mysql_user -p$mysql_passwd -h$mysql_host)
db_arr=${db_arr/Database/''}
db_arr=${db_arr/information_schema/''}
db_arr=${db_arr/mysql/''}
#echo $db_arr  
#不需要备份的单例数据�? 
#nodeldb=""  
  
#当前日期  
date=$(date -d '+0 days' +%Y%m%d)  
  
#zip打包密码  
#zippasswd="passwd"  
tarname="mst_"$date".tar.gz"  
  
  
#2.进入到备份目�? 
cd $back_dir  
  
  
#3.循环备份  
for dbname in ${db_arr}  
do
 #    if [ $dbname != $nodeldb ]; then  
        sqlfile=$dbname-$date".sql"  
        mysqldump -u$mysql_user -p$mysql_passwd -h$mysql_host $dbname >$sqlfile  
  #   fi  
done  
  
  
#4.tar打包所有的sql文件  
#tar -zcPpf $root_dir/$store_dir/$zipname --directory /  $root_dir/$data_dir  
mkdir $root_dir/$store_dir
sudo tar -zcPpf $root_dir/$store_dir/$tarname  $root_dir/$data_dir
#打包成功后删除sql文件  
if [ $? = 0 ]; then  
    rm -r $root_dir/$data_dir  
fi  
