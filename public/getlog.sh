#!/bin/bash
HOST="localhost"
USER="root"
PASS="MySQLSmartTools"
PORT=""
log_file=`mysql -h$HOST -u$USER -p$PASS -e"use information_schema;select VARIABLE_VALUE  from GLOBAL_VARIABLES where VARIABLE_NAME ='GENERAL_LOG_FILE';"  | sed '1d'`
#log_file=${log_file#*VARIABLE_VALUE}

#cat $log_file | grep Connect | grep : > ./option.log
sudo cat $log_file | egrep '[0-9]+\s+[0-9]+:[0-9]+:[0-9]+\s+.*' | grep Connect > /var/www/html/MSTV2.0/public/login_record.log