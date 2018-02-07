#! /bin/bash
MYSQL_USER="root"
MYSQL_PWD="MySQLSmartTools"
MYSQL_HOST="localhost"

SLOW_QUERY_LOG_INFO=`mysql -h$MYSQL_HOST -u$MYSQL_USER -p$MYSQL_PWD -e"
use information_schema;
select VARIABLE_VALUE  from GLOBAL_VARIABLES where VARIABLE_NAME like '%slow_query_log%';" | sed '1d'`

IS_SLOW_QUERY_LOG_ON=`echo $SLOW_QUERY_LOG_INFO | awk '{print $1}'`

SLOW_QUERY_LOG_FILE=`echo $SLOW_QUERY_LOG_INFO | awk '{print $2}'`

if [ "$IS_SLOW_QUERY_LOG_ON" != "ON" ]
then
   `echo "Warning: Please check the slow_query_log is open or not!" > /var/www/html/MSTV2.0/public/slowquery.txt`

elif [ -f $SLOW_QUERY_LOG_FILE ]
then
     mysqldumpslow  $SLOW_QUERY_LOG_FILE > /var/www/slow.log

     cat /var/www/slow.log | sed 's/^$/###/g' | tr -d '\n' | sed 's/###/\n/g' | sed -e 's/     \+//g' | sed 's/: /:/g' | sed 's/  Time/##Time/g' | sed 's/  Lock/##Lock/g' | sed 's/  Rows/##Rows/g' | sed 's/\(.*@[^[:space:]]*\)  /\1##/g' > /var/www/html/MSTV2.0/public/slowquery.txt

     chmod 777 /var/www/slowquery.txt
     rm -rf /var/www/slow.log
fi
