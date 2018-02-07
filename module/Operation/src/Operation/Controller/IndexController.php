<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        //specifications
        $specifications = array(
            'Connections','MySQL Threads', 'Bytes','QPS&TPS','Key Buffer','Innodb Buffer','Query Cache'
        );
        
        //socket
        $result = mysql_query("show variables like 'socket';");
        $row = mysql_fetch_row($result);
        $socket = $row[1];
        
        //port
        $result = mysql_query("show variables like 'port';");
        $row = mysql_fetch_row($result);
        $port = $row[1];                 
        
        //mysql version
        $result = mysql_query("select version();");
        $row = mysql_fetch_row($result);
        $version = $row[0];
        
        //mysql engine
        $result = mysql_query("show variables like 'storage_engine';");
        $row = mysql_fetch_row($result);
        $engine = $row[1];                    
        
        //collation
        $result = mysql_query("show variables like 'collation_connection';");
        $row = mysql_fetch_row($result);
        $collation = $row[1];
        
        //running uptime
        $str = \explode(" ", \implode("",\file("/proc/uptime")));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) $res['uptime'] = $days." days ";
        if ($hours !== 0) $res['uptime'] .= $hours." hours ";
        $res['uptime'] .= $min." minutes";
        $uptime = $res['uptime'];
        
        //bytes_sent
        mysql_select_db('MySQL-Smart-Tools-DB');
        $sql = 'select Bytes_sent from mysql_bytes_history order by id desc limit 1';
        $rs = mysql_query($sql);
        $row = mysql_fetch_row($rs);
        $bytes_sent = round($row[0],1);
                
        return new ViewModel(
            array(
                'specifications' => $specifications,
                'socket' => $socket,
                'port' => $port,
                'version' => $version,
                'engine' => $engine,
                'collation' => $collation,
                'uptime' => $uptime,
                'bytes_sent' => $bytes_sent,
            )
        );
    }
}
