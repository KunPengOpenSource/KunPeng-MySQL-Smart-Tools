<?php
    switch ($tid) {
        case 0:
            $name = 'Connections';
            $table = 'mysql_connections';
            $unit = 'pcs';
            $options = array('Max_connections','Max_used_connections','Threads_connections','Threads_running');
                break;
        case 1:
            $name = 'Threads';
            $table = 'mysql_threads';
            $unit = 'pcs';
            $options = array('Threads_running','Threads_connected');
                break;
        case 2:
            $name = 'Bytes';
            $table = 'mysql_bytes_history';
            $unit = 'KB';
            $options = array('Bytes_sent','Bytes_received');
                break;
        case 3:
            $name = 'QPS&TPS';
            $table = 'mysql_qps_tps_history';
            $unit = 'pcs';
            $options = array('QPS','TPS');
                break;
        case 4:
            $name = 'Key Buffer';
            $table = 'mysql_key_buffer_history';
            $unit = '%';
            $options = array('key_buffer_read_hits','key_buffer_write_hits');
                break;
        case 5:
            $name = 'Innodb Buffer';
            $table = 'mysql_innodb_buffer_history';
            $unit = '%';
            $options = array('innodb_buffer_hits');
                break;
        case 6:
            $name = 'Query Cache';
            $table = 'mysql_query_cache_history';
            $unit = '%';
            $options = array('Query_cache_hits');
                break;
        case 7:
            $name = 'Thread Cache';
            $table = 'mysql_thread_cache_history';
            $unit = '%';
            $options = array('thread_cache_hits');
                break;
        case 8:
            $name = 'Query Throughput Rate';
            $table = 'mysql_query_throughput_rate_history';
            $unit = 'pcs';
            $options = array('Changedb','Com_select','Com_insert','Com_update','Com_delete');
                break;
    }