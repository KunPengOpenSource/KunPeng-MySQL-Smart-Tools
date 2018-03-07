<?php
    include('switchtid.php');
    
    switch ($id) {
    case 1:
        $num = 60;
        $space = 1;
        $title = '60 Minutes';
        $type = "%H:%M";
            break;
    case 2:
        $num = 480;
        $space = $num/120;
        $title = '8 Hours';
        $type = "%H:00";
            break;
    case 3:
        $num = 1440;
        $space = $num/120;
        $title = '24 Hours';
        $type = "%H:00";
            break;
    case 4:
        $num = 10080;
        $space = $num/120;
        $title = '1 Week';
        $type = "%y/%m/%d";
            break;
    case 5:
        $num = 43200;
        $space = $num/120;
        $title = '1 Month';
        $type = "%y/%m/%d";
            break;
    case 6:
        $num = 129600;
        $space = $num/120;
        $title = '3 Months';
        $type = "%y/%m/%d";
            break;
}
    mysql_connect('localhost', 'root', 'MySQLSmartTools');
    mysql_select_db('MySQL-Smart-Tools-DB');
    $sql = 'select * from (select * from '.$table.' order by id desc limit '.$num.') as newtable order by id asc';
    $result = mysql_query($sql);