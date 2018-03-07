<?php

namespace Operation\Model;
class Export{
    public $db,$table,$crlf;
//    ini_set("max_execution_time", "180");//避免数据量过大，导出不全的情况出现。
//    $filename=date("Y-m-d_H-i-s")."-".$dbname.".sql";
//    header("Content-disposition:filename=".$filename);
//    header("Content-type:application/octetstream");
//    header("Pragma:no-cache");
//    header("Expires:0");
//
//    //备份数据
//    $i = 0;
//    $crlf="\r\n";
//    global $dbconn;
//    $dbconn = mysql_connect('localhost', 'root','MySQLSmartTools');
//    $db = mysql_select_db($dbname,$dbconn);
//    mysql_query("SET NAMES 'utf8'");
//    $tables =mysql_list_tables($dbname,$dbconn);
//    $num_tables = @mysql_numrows($tables);
//    print "-- filename=".$filename;
//    while($i < $num_tables)
//    {
//        $table=mysql_tablename($tables,$i);
//        print $crlf;
//        echo get_table_structure($dbname, $table, $crlf).";$crlf$crlf";
//        //echo get_table_def($dbname, $table, $crlf).";$crlf$crlf";
//        echo get_table_content($dbname, $table, $crlf);
//        $i++;
//    }

    /*新增的获得详细表结构*/
    function get_table_structure($db,$table,$crlf)
    {
        global $drop;
        $schema_create = "";
        if(empty($drop)){ 
            $schema_create .= "DROP TABLE IF EXISTS `$table`;$crlf";
        }
        $result =mysql_db_query($db, "SHOW CREATE TABLE $table");
        $row=mysql_fetch_array($result);
        $schema_create .= $crlf."-- ".$row[0].$crlf;
        $schema_create .= $row[1];
        return $schema_create;
    }

    //获得表内容
    function get_table_content($db, $table, $crlf){
        $schema_create = "";
        $temp = "";
        $result = mysql_db_query($db, "SELECT * FROM $table");
        $i = 0;
        while($row = mysql_fetch_row($result)){
            $schema_insert = "INSERT INTO `$table` VALUES (";
            for($j=0; $j<mysql_num_fields($result);$j++)  {
                if(!isset($row[$j]))
                    $schema_insert .= " NULL,";
                elseif($row[$j] != "")
                    $schema_insert .= " '".addslashes($row[$j])."',";
                else
                    $schema_insert .= " '',";
                }
                $schema_insert = ereg_replace(",$", "",$schema_insert);
                $schema_insert .= ");$crlf";
                $temp = $temp.$schema_insert ;
                $i++;
            }
        return $temp;
    }
}
?> 

