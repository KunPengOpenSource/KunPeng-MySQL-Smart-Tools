    #!/bin/bash -   
      
    #1.参数配置  
      
    #mysql文件备份目录  
    backup_dir="/var/www/html/MSTV2.0/public/data/backup/database/"  
      
    #过期文件的时�? 
    keep_time=14  
      
    #当前所在星期，crontab在奇数的星期7执行  
    week=$(date +%W)  
    flag=`expr $week % 2`  
      
      
      
    #2.清理过期文件,只在奇数星期7执行  
    if [ $flag -eq 1 ]; then  
       #查找14天之外的文件数据  
       clean_arr=`find $backup_dir -type f -mtime +$keep_time -exec ls {} \;`  
       for cleanfile in ${clean_arr}  
       do  
           rm -rf $cleanfile  
       done  
   fi  
