<?php
    namespace Operation\Controller;
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;
    
    Class AnalysisController extends AbstractActionController{
        public function indexAction() {
            $specifications = array(
                'Connections','MySQL Threads', 'Bytes','QPS&TPS','Key Buffer','Innodb Buffer','Query Cache','Threads Cache','Query Throughput'
            );
            return new ViewModel(
                array(
                    'specifications' => $specifications
                )
            );
        }
        
        public function specificationAction(){
            $specification = $this->params()->fromRoute('specification');
            
            $specifications = array(
                 'Connections','MySQL Threads', 'Bytes','QPS&TPS','Key Buffer','Innodb Buffer','Query Cache','Threads Cache','Query Throughput'
            );
            return new ViewModel(
                array(
                    'specification' => $specification,
                    'specifications' => $specifications
                )
            );
        }
        
        public function ylanAction(){
            
            $id = $_POST['id'];
            $tid = $_POST['tid'];
            //include('data.php');
            //include('switchtid.php');
            
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
                    $unit = 'degree';
                    $options = array('Changedb','Com_select','Com_insert','Com_update','Com_delete');
                        break;
            }

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
            
            date_default_timezone_set("Asia/Shanghai");
            $time = array();
            $str = "";
            $option = array();
            $od = array();
            foreach ($options as $key => $value) {
                $option[$value] = "";
            }
            $i = 0;
            foreach ($options as $key => $value) {
                $od[$key] = 0;
            }
            while ($row = mysql_fetch_assoc($result)) {
                if($i < $space){
                    foreach ($options as $key => $value) {
                        $od[$key] += $row[$value];
                    }
                }
                if($i == $space){
                    $strspace = "";
                    foreach ($options as $key => $value) {
                        $strspace .= round($od[$key]/$space) ."#";
                    }
                    $str .= date('Y-m-d H:i', strtotime($row['create_time'])) . "#" . $strspace . "#";
                    $i=0;
                    foreach ($options as $key => $value) {
                        $od[$key] = $row[$value];
                    }
                    $time[] = $row['create_time'];
                }
                $i++;
                foreach ($options as $key => $value) {
                    $option[$value] = "";
                }
            }
            $data = array(
                'str' => $str,
                'status' => 1,
                'option' => $options,
            );
            echo json_encode($data);
            die;
        }
        
        public function downloadAction(){
            $tid = $_GET['tid'];
            $id = $_GET['id'];
//            include("data.php");
            
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
                    $unit = 'degree';
                    $options = array('Changedb','Com_select','Com_insert','Com_update','Com_delete');
                        break;
            }

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
            
            
            $file_type = "vnd.ms-excel";
            $file_ending = 'csv';
            $title = "The_last_" . strtolower(str_replace(" ", "_", $title)) ."_on_the_". strtolower(str_replace(" ", "_", $name)) ."_of_data";
            $filename = $title.".".$file_ending;
            header("Content-Type:application/$file_type");
            header("Content-Disposition:attachment;filename=$filename");
            echo $title . "
        ";
            $field = "";
            for($i = 0; $i < mysql_num_fields($result); $i++){
                $field .= mysql_field_name($result, $i) . ",";
            }
            echo $field;
            print "
        ";
            $time = array();
            while ($row = mysql_fetch_array($result)) {
                $data = "";
                for($j = 0; $j<mysql_num_fields($result); $j++){
                    $data .= $row[$j] . ",";
                }
                $time[] = $row['create_time'];
                echo $data;
                print "
        ";
            }
            echo "all data from " . reset($time) . " to " .end($time) . "
        ";
            die;
        }
    }
?>

