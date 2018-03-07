#! /bin/bash
HOST="localhost"
USER="root"
PASS="MySQLSmartTools"
PORT=""
log_file=`mysql -h$HOST -u$USER -p$PASS -e"use information_schema;select VARIABLE_VALUE  from GLOBAL_VARIABLES where VARIABLE_NAME ='GENERAL_LOG_FILE';"  | sed '1d'`
mv $log_file ${log_file}.$(date +%Y%m%d)
touch $log_file
chown mysql:mysql $log_file
/usr/bin/mysqladmin -p$PASS flush-logs
rm -rf ${log_file}.$(date +%Y%m%d -d '7 days ago')
