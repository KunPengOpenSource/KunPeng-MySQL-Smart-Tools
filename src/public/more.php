<?php
    if(isset($_POST['username'])&&isset($_POST['host'])){
        $username = $_POST['username'];
        $host = $_POST['host'];
//        include('./mysql_connect.php');
//        $mysql = new mysql;
//        $mysql->connect();
//        $mysql->select_db('mysql');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $sql = 'select * from db where user="' . $username . '" and host="' .$host.'"';
        $rs = mysql_query($sql);
        $i=1;
        $str = '';
        while ($row = mysql_fetch_row($rs)) {
            $str .= "<li class=\"child\" id=\"child{$i}\">";
            $str .= "<table><tr><td colspan='3'><div class='dbname'>{$row[1]}</div></td><tr><tr id=\"ch\">";
            $str .= "<td><ul><li>Object Rights:&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[3]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"select\" />&nbsp;Select&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[4]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"insert\" />&nbsp;Insert&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[5]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"update\" />&nbsp;Update&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[6]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"delete\" />&nbsp;Delete&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[19]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"execute\" />&nbsp;Execute&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[16]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"showview\" />&nbsp;Show View&nbsp;</li></ul></td> ";
            $str .= "<td><ul><li>DDL Rights:&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[7]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"create\" />&nbsp;Create&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[12]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"alter\" />&nbsp;Alter&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[10]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"reference\" />&nbsp;Reference&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[11]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"index\" />&nbsp;Index&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[15]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"createview\" />&nbsp;Create View&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[17]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"createroutine\" />&nbsp;Create Routine&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[18]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"alterroutine\" />&nbsp;Alter Routine&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[20]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"event\" />&nbsp;Event&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[8]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"drop\" />&nbsp;Drop&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[21]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"trigger\" />&nbsp;Trigger&nbsp;</li></ul></td>";
            $str .= "<td><ul><li>Others Rights:&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[3]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"grantoption\" />&nbsp;Grant Option&nbsp;</li>";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[6]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"createtemporary\" />&nbsp;Create Temporary&nbsp;</li> ";
            $str .= "<li><input type=\"checkbox\" name=\"ObjectR\"";
            $check = $row[16]=="Y" ? "checked" : "";
            $str .= $check . " disabled=\"true\" value=\"locktables\" />&nbsp;Lock Tables&nbsp;</li></ul></td></tr></table></li>";
            $i++;
        } 
        $arr = array(
            'str' => $str,
            'status' => 1
        );
        echo json_encode($arr);
    }