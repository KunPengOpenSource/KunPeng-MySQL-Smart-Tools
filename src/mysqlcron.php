#!/usr/bin/php -q
<?php
$sleep = 60;      //取值睡眠时间间隔
$filepath = './data/';    //文件保存路径
class conn{
    public $sql = '';
    public $db='information_schema';
    protected $host='localhost';
    protected $user='root';
    protected $pwd='MySQLSmartTools';
    public function connect(){
	$link = mysql_connect($this->host,$this->user,$this->pwd);
	if($link){
	    echo 'connection is success';
	}else{
	    echo mysql_error() or die();
	}
        return $link;
    }

    public function charset(){
	$this->query('set names utf8');
    }

    public function select_db($db){
	mysql_select_db($db);
    }

    public function query($sql){
	return mysql_query($sql);
    }

    public function close(){
	mysql_close();
    }
}

//连接数据库
$mysqlconn = new conn();
$link = $mysqlconn->connect();
$time = time();
if($link){
    $mysqlconn->charset();
    $mysqlconn->select_db($mysqlconn->db);

    $sql_qcache_inserts = 'select VARIABLE_VALUE  from GLOBAL_STATUS where VARIABLE_NAME ="Qcache_inserts"';
    $sql_qcache_hits = 'select VARIABLE_VALUE  from GLOBAL_STATUS where VARIABLE_NAME ="Qcache_hits"';

    $sql_max_connections = 'select variable_value from information_schema.GLOBAL_VARIABLES where variable_name="Max_connections"';
    $sql_max_used_connections = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="Max_used_connections"';
    $sql_threads_connected = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="Threads_connected"';
    $sql_threads_running = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="Threads_running"';
    $sql_threads_created = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="theads_created"';
    $sql_connections = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="connections"';

    $sql_innodb_buffer_pool_reads = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="innodb_buffer_pool_reads"';
    $sql_innodb_buffer_pool_read_requests = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="innodb_buffer_pool_read_requests"';

    $sql_key_reads = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="key_reads"';
    $sql_key_read_requests = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="key_read_requests"';
    $sql_key_writes = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="key_writes"';
    $sql_key_write_requests = 'select variable_value from information_schema.GLOBAL_STATUS where variable_name="key_write_requests"';

    $sql_change_db = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="Com_change_db"';
    $sql_com_select = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="Com_select"';
    $sql_com_insert = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="Com_insert"';
    $sql_com_update = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="Com_update"';
    $sql_com_delete = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME ="Com_delete"';

    $sql_bytes_sent = 'select VARIABLE_VALUE  from GLOBAL_STATUS where VARIABLE_NAME="bytes_sent"';
    $sql_bytes_received = 'select VARIABLE_VALUE  from GLOBAL_STATUS where VARIABLE_NAME="bytes_received"';

    $sql_queries = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME="Queries"';
    $sql_com_commit = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME="Com_commit"';
    $sql_com_rollback = 'select VARIABLE_VALUE  from information_schema.GLOBAL_STATUS where VARIABLE_NAME="Com_rollback"';

    //获取数据
    $Qcache_inserts = mysql_fetch_row($mysqlconn->query($sql_qcache_inserts));
    $Qcache_hits = mysql_fetch_row($mysqlconn->query($sql_qcache_hits));
    $Max_connections = mysql_fetch_row($mysqlconn->query($sql_max_connections));
    $Max_used_connections = mysql_fetch_row($mysqlconn->query($sql_max_used_connections));
    $Threads_connected = mysql_fetch_row($mysqlconn->query($sql_threads_connected));
    $Threads_created = mysql_fetch_row($mysqlconn->query($sql_threads_created));
    $Threads_running = mysql_fetch_row($mysqlconn->query($sql_threads_running));
    $Connections = mysql_fetch_row($mysqlconn->query($sql_connections));
    $Innodb_buffer_pool_reads = mysql_fetch_row($mysqlconn->query($sql_innodb_buffer_pool_reads));
    $Innodb_buffer_pool_read_requests = mysql_fetch_row($mysqlconn->query($sql_innodb_buffer_pool_read_requests));
    $Key_reads = mysql_fetch_row($mysqlconn->query($sql_key_reads));
    $Key_reads_requests = mysql_fetch_row($mysqlconn->query($sql_key_read_requests));
    $Key_writes = mysql_fetch_row($mysqlconn->query($sql_key_writes));
    $Key_write_requests = mysql_fetch_row($mysqlconn->query($sql_key_write_requests));

    $Change_db = mysql_fetch_row($mysqlconn->query($sql_change_db));
    $Com_select = mysql_fetch_row($mysqlconn->query($sql_com_select));
    $Com_insert = mysql_fetch_row($mysqlconn->query($sql_com_insert));
    $Com_update = mysql_fetch_row($mysqlconn->query($sql_com_update));
    $Com_delete = mysql_fetch_row($mysqlconn->query($sql_com_delete));
    $Bytes_sent = mysql_fetch_row($mysqlconn->query($sql_bytes_sent));
    $Bytes_received = mysql_fetch_row($mysqlconn->query($sql_bytes_received));
    $Queries = mysql_fetch_row($mysqlconn->query($sql_queries));
    $Com_commit = mysql_fetch_row($mysqlconn->query($sql_com_commit));
    $Com_rollback = mysql_fetch_row($mysqlconn->query($sql_com_rollback));

    sleep($sleep);      //进入睡眠

    $Change_db2 = mysql_fetch_row($mysqlconn->query($sql_change_db));
    $Com_select2 = mysql_fetch_row($mysqlconn->query($sql_com_select));
    $Com_insert2 = mysql_fetch_row($mysqlconn->query($sql_com_insert));
    $Com_update2 = mysql_fetch_row($mysqlconn->query($sql_com_update));
    $Com_delete2 = mysql_fetch_row($mysqlconn->query($sql_com_delete));
    $Bytes_sent2 = mysql_fetch_row($mysqlconn->query($sql_bytes_sent));
    $Bytes_received2 = mysql_fetch_row($mysqlconn->query($sql_bytes_received));
    $Queries2 = mysql_fetch_row($mysqlconn->query($sql_queries));
    $Com_commit2 = mysql_fetch_row($mysqlconn->query($sql_com_commit));
    $Com_rollback2 = mysql_fetch_row($mysqlconn->query($sql_com_rollback));

    //数据处理
    $change_db = round(($Change_db2[0] - $Change_db[0])/$sleep, 3);
    $com_select = round(($Com_select2[0] - $Com_select[0])/$sleep, 3); 
    $com_insert = round(($Com_insert2[0] - $Com_insert[0])/$sleep, 3);
    $com_update = round(($Com_update2[0] - $Com_update[0])/$sleep, 3);
    $com_delete = round(($Com_delete2[0] - $Com_delete[0])/$sleep, 3);
    $bytes_sent = round((($Bytes_sent2[0] - $Bytes_sent[0])/$sleep)/1000, 3);
    $bytes_received = round((($Bytes_received2[0] - $Bytes_received[0])/$sleep)/1000, 3);
    $QPS = round(($Queries2[0] - $Queries[0])/$sleep, 3);
    $TPS = round(($Com_commit2[0] + $Com_rollback2[0] - $Com_rollback[0] -$Com_commit[0])/$sleep, 3);
    $innodb_buffer_hits = round((1-($Innodb_buffer_pool_reads[0] / $Innodb_buffer_pool_read_requests[0])) * 100, 3);
    $key_buffer_read_hits = round((1-($Key_reads[0] / $Key_reads_requests[0])) * 100, 3);
    $key_buffer_write_hits = round((1-($Key_writes[0] / $Key_write_requests[0])) * 100, 3);
    $query_cache_hits = round($Qcache_hits[0] / ($Qcache_hits[0] + $Qcache_inserts[0]) * 100, 3);
    $thread_cache_hits = round((1 - $Threads_created[0] / $Connections[0]) * 100, 3);
    
    $max_connections = (int)$Max_connections[0];
    $max_used_connections = (int)$Max_used_connections[0];
    $threads_connected = (int)$Threads_connected[0];
    $threads_created = (int)$Threads_created[0];
    $threads_running = (int)$Threads_running[0];
    $connections = (int)$Connections[0];
    
    $mysqlconn->close();       //关闭数据库连接
    
}else{
    //数据库连接失败，数据为0；
    $bytes_received = 0;
    $bytes_sent = 0;
    $Max_connections = 0;
    $Max_used_connections = 0;
    $Threads_connected = 0;
    $Threads_running = 0;
    $QPS = 0;
    $TPS = 0;
    $innodb_buffer_hits = 0;
    $key_buffer_read_hits = 0;
    $key_buffer_write_hits = 0;
    $query_cache_hits = 0;
    $change_db = 0;
    $com_select = 0;
    $com_insert = 0;
    $com_update = 0;
    $com_delete = 0;
    $thread_cache_hits = 0;
    $Threads_running = 0;
    $Threads_connected = 0;
}
//组合数据
$mysql_bytes_get = array('bytes_received' => $bytes_received, 'bytes_sent' => $bytes_sent, 'time' => $time);
$mysql_connections_get = array('Max_connections' => $max_connections, 'Max_used_connections' => $max_used_connections, 'Threads_connected' => $threads_connected, 'Threads_running' => $threads_running, 'time' => $time);
$mysql_qps_tps_get = array('QPS' => $QPS, 'TPS' => $TPS, 'time' => $time);
$mysql_innodb_buffer_get = array('innodb_buffer_hits' => $innodb_buffer_hits, 'time' => $time);
$mysql_key_buffer_get = array('key_buffer_read_hits' => $key_buffer_read_hits, 'key_buffer_write_hits' => $key_buffer_write_hits,'time' => $time);
$mysql_query_cache_get = array('query_cache_hits' => $query_cache_hits, 'time' => $time);
$mysql_query_throughput_rate_get = array('change_db' => $change_db, 'com_select' => $com_select, 'com_insert' => $com_insert, 'com_update' => $com_update, 'com_delete' => $com_delete, 'time' => $time);
$mysql_thread_cache_get = array('thread_cache_hits' => $thread_cache_hits, 'time' => $time);
$mysql_threads_get = array('threads_running' => $threads_running, 'threads_connected' => $threads_connected, 'time' => $time);

//echo json_encode($mysql_bytes_get);
//echo json_encode($mysql_connections_get);
//echo json_encode($mysql_qps_tps_get);
//echo json_encode($mysql_innodb_buffer_get);
//echo json_encode($mysql_key_buffer_get);
//echo json_encode($mysql_query_cache_get);
//echo json_encode($mysql_query_throughput_rate_get);
//echo json_encode($mysql_thread_cache_get);
//echo json_encode($mysql_threads_get);

//保存数据
saveData($filepath, $mysql_bytes_get);
saveData($filepath, $mysql_connections_get);
saveData($filepath, $mysql_qps_tps_get);
saveData($filepath, $mysql_innodb_buffer_get);
saveData($filepath, $mysql_key_buffer_get);
saveData($filepath, $mysql_query_cache_get);
saveData($filepath, $mysql_query_throughput_rate_get);
saveData($filepath, $mysql_thread_cache_get);
saveData($filepath, $mysql_threads_get);

//保存json数据
function saveData($filepath, $data){
    $str = getVarName($data);                //获取变量名
    $file = $filepath . $str . '_file.json';
    if(!file_exists($file)){                 //文件初始化
        $handle = fopen($file, 'w');
        fwrite($handle, '[' . json_encode($data) . ']');
        fclose($handle);
    }else{
        $txt = file_get_contents($file);
        $txt = str_replace(']', ',' . json_encode($data) .  ']', $txt);
        file_put_contents($file, $txt);
    }
}
//获取变量名称
function getVarName(&$var, $scope = NULL) {
    if (NULL == $scope) {
      $scope = $GLOBALS;
    }
//    $tmp = $var;
//    $var = "tmp_exists_" . mt_rand();
    $name = array_search($var, $scope, TRUE);
//    print_r($name);
//    $var = $tmp;
    return $name;
}