<?php
    namespace Operation\Controller;
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;
    use Operation\Model\Export;
    use Operation\Model\Import;
    
    Class DatabaseController extends AbstractActionController{
        public function indexAction() {
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);  
            $sql = 'show databases';
            $result = mysql_query($sql);
            return new ViewModel(
                array(
                    'result' => $result,
                )
            );
        }
     
        public function createDatabaseAction(){}
    
        public function createDatabaseHandleAction(){
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            $request = $this->getRequest();
            if($request->isPost()){
                $sql="CREATE database IF NOT EXISTS `{$_POST['dbName']}`";
//                echo $sql;
                if(mysql_query($sql)){
                    return $this->redirect()->toRoute('database', array('action' => 'index'));
                }else {
                    return new ViewModel(
                        array(
                            'error' => mysql_error(),
                        )    
                    );
                }
            }
        }

        public function dbdropAction(){
           // $dbname = $this->params()->fromRoute('dbname', '');
            $dbname=$_POST['dbname'];
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            $sql="drop database $dbname";
            if(mysql_query($sql)){
                $data = array('status' => 1);
            }else{
                $data = array('status' => 0);
            }
            echo \json_encode($data);
            die;
        }
        
        public function dbexportAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
//            $tbname = $this->params()->fromRoute('tbname', '');
            ini_set("max_execution_time", "180");//避免数据量过大，导出不全的情况出现。
            $filename=$dbname.".sql";
            header("Content-disposition:filename=".$filename);
            header("Content-type:application/octetstream");
            header("Pragma:no-cache");
            header("Expires:0");

            //备份数据
            $i = 0;
            $crlf="\r\n";
            global $link;
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $tables =mysql_list_tables($dbname,$link);
            $num_tables = mysql_num_rows($tables);
            print "-- filename=".$filename;
            
            while($i < $num_tables){
                $table=mysql_tablename($tables,$i);
                print $crlf;
                global $drop;
                $schema_create = "";
                if(empty($drop)){ 
                    $schema_create .= "DROP TABLE IF EXISTS `$table`;$crlf";
                }
                $result =mysql_db_query($dbname, "SHOW CREATE TABLE `$table` ");
                $row=mysql_fetch_array($result);
                $schema_create .= $crlf."-- ".$row[0].$crlf;
                $schema_create .= $row[1].";";
                echo $schema_create.$crlf;
                $schema_create = "";
                $temp = "";
                $result = mysql_db_query($dbname, "SELECT * FROM `$table`");
//                $k = 0;
                while($row = mysql_fetch_row($result)){
                    $schema_insert = "INSERT INTO `$table` VALUES (";
                    for($j=0; $j<mysql_num_fields($result);$j++)  { //mysql_num_fields() 函数返回结果集中字段的数
                        if(!isset($row[$j]))
                            $schema_insert .= " NULL,";
                        elseif($row[$j] != "")
                            $schema_insert .= " '".addslashes($row[$j])."',"; //addslashes() 函数在指定的预定义字符前添加反斜杠。这些预定义字符是：单引号 (') 双引号 (") 反斜杠 (\) NULL

                        else
                            $schema_insert .= " '',";
                   }
                        $schema_insert = ereg_replace(",$", "",$schema_insert);
                        $schema_insert .= ");$crlf";
                        $temp = $temp.$schema_insert ;
//                        $k++;
                }
                echo $temp;
                $i++;
            }    
            die;
        }
        
         public function dbimportAction(){
               $dbname = $this->params()->fromRoute('dbname', '');
               return new ViewModel(
               array(
                  'dbname' => $dbname,
               )    
               );
         }
        
         public function dbImportHandleAction(){  
            $upload = new Import();
            $upload->setMaxSize(2);
            $importfile = $upload->upload('importfile');
            $data['importfile'] = $importfile;
            $dbname = isset($_POST['dbname'])?$_POST['dbname']:'';
            if($data['importfile']){
                $message = 'file upload success ! ';
                $link = mysql_connect('localhost','root','MySQLSmartTools');
                mysql_query('set names utf8');
                mysql_select_db($dbname,$link);

                $templine = '';
                $lines = file($data['importfile']);
                // Loop through each line
                foreach ($lines as $line)
                {
                    // Skip it if it's a comment
                    if (substr($line, 0, 2) == '--' || $line == '')
                        continue;

                    // Add this line to the current segment
                    $templine .= $line;
                    // If it has a semicolon at the end, it's the end of the query
                    if (substr(trim($line), -1, 1) == ';')
                    {
                        // Perform the query
                        mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                        // Reset temp variable to empty
                        $templine = '';
                    }
                }
                $message .= 'file imported success ! ';
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'message' => $message
                    )
                );
            }else{
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'message' => 'file upload error' . $upload->getErr()
                    )
                );
            }
        }
         
        public function tblistAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            mysql_select_db($dbname);
            $sql= "show tables";
            //$sql = 'select table_name from information_schema.tables where table_schema = "' . $dbname . '" and  table_type = "BASE TABLE"';
            $result = mysql_query($sql);
            if($result){
                 return new ViewModel(
                 array(
                    'dbname' => $dbname,
                    'result' => $result,
                )
            );
            }
        }
        
        public function createTableAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            return new ViewModel(
                array(
                    'dbname' => $dbname,
                )
            );
        }
        
        public function createTableHandleAction(){
            $link = mysql_pconnect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            $request = $this->getRequest();
            if($request->isPost()){
                $dbname = $_POST['dbname'];
                mysql_select_db($dbname);
                $tbname = $_POST['tablename'];
                $data = array(
                    'name' => $_POST['name'],'type' => $_POST['type'],'length' => $_POST['length'],'attr' => $_POST['attr'],'index' => $_POST['index'],
                );
                
                $sql = 'create table ' . $tbname . '(';
                $column = array();
                foreach ($data as $key=>$value) {
                    foreach($value as $k=>$v){
                        if(!empty($v)){
                            $column[$k][$key] = $v;
                        }
                    }
                }
                $n_column = array();
                foreach ($column as $key => $value) {
                    if(empty($value['name'])){
                        continue;
                    }
                    $n_column[] = $value;
                }
                $len = count($n_column);
                $i = 0;
                foreach($n_column as $value){
                    $sql .= $value['name'] . ' ' . $value['type'] . '(' . $value['length'] . ') ' . $value['attr'] . ' ' . $value['index'] . (($i==$len-1)? '': ',');
                    $i++;
                }
                $sql .= ')';
                if(mysql_query($sql)){
                    return $this->redirect()->toRoute('database', array('action' => 'tblist', 'dbname' => $dbname));
                }  else {
                    return new ViewModel(
                        array(
                            'error' => mysql_error()
                        )    
                    );
                }
            }
        }
        
        public function tbStructureAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $sql = 'desc ' . $tbname;
            if($result = mysql_query($sql)){
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'result' => $result
                    )   
                );
            }
        }
        
        public function tbAddColumnAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $request = $this->getRequest();
            if($request->isPost()){
                $column = $_POST['column'];
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'column' => $column
                    )
                );
            }
        }
        
        public function tbAddColumnHandleAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $link = mysql_pconnect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            $request = $this->getRequest();
            if($request->isPost()){
                mysql_select_db($dbname);
                $data = array(
                    'name' => $_POST['name'],'type' => $_POST['type'],'length' => $_POST['length'],'attr' => $_POST['attr'],'index' => $_POST['index'],
                );
                
                $sql = 'create table if not exists ' . $tbname . '(';
                $sql = 'alter table ' . $tbname;
                $column = array();
                foreach ($data as $key=>$value) {
                    foreach($value as $k=>$v){
                        if(!empty($v)){
                            $column[$k][$key] = $v;
                        }
                    }
                }
                $n_column = array();
                foreach ($column as $key => $value) {
                    if(empty($value['name'])){
                        continue;
                    }
                    $n_column[] = $value;
                }
                $len = count($n_column);
                $i = 0;
                foreach($n_column as $value){
                    $sql .= ' add ' . $value['name'] . ' ' . $value['type'] . '(' . $value['length'] . ') ' . $value['attr'] . ' ' . $value['index'] . (($i==$len-1)? '': ',');
                    $i++;
                }
                if(mysql_query($sql)){
                    return $this->redirect()->toRoute('database', array('action' => 'tbstructure', 'dbname' => $dbname, 'tbname' => $tbname));
                }  else {
                    return new ViewModel(
                        array(
                            'error' => mysql_error()
                        )    
                    );
                }
            }
        }
        
        public function tbEditColumnAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $column = $this->params()->fromRoute('column', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $sql = 'show columns from ' . $tbname . ' where field = \'' . $column . '\'';
            $result = mysql_query($sql);
            if($result){
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'result' => $result
                    )
                );
            }
        }
        
        public function tbEditColumnHandleAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
        }
        
        public function tbDropColumnAction(){
            $dbname = $_POST['dbname'];
            $tbname = $_POST['tbname'];
            $column = $_POST['colname'];
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $sql = 'alter table ' . $tbname . ' drop ' . $column;
            if(mysql_query($sql)){
                    $data = array('status' => 1);
            }else{
                    $data = array('status' => 0);
            }
           echo \json_encode($data);
           die;
        }
        
        public function tbPriKeyAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $column = $this->params()->fromRoute('column', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $sql = 'alter table ' . $tbname . ' add primary key(' . $column . ')';
            if(mysql_query($sql)){
                $this->redirect()->toRoute('database', array('action' => 'tbstructure', 'dbname' => $dbname, 'tbname' => $tbname));
            }else{
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'message' => mysql_error()
                    )
                );
            }
        }
        
        public function tbUniqueAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $column = $this->params()->fromRoute('column', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $sql = 'alter table ' . $tbname . ' add unique (' . $column . ')';
            if(mysql_query($sql)){
                $this->redirect()->toRoute('database', array('action' => 'tbstructure', 'dbname' => $dbname, 'tbname' => $tbname));
            }else{
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'message' => mysql_error()
                    )
                );
            }
        }
        
        public function tbIndexAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            $column = $this->params()->fromRoute('column', '');
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
            $sql = 'alter table ' . $tbname . ' add index (' . $column . ')';
            if(mysql_query($sql)){
                $this->redirect()->toRoute('database', array('action' => 'tbstructure', 'dbname' => $dbname, 'tbname' => $tbname));
            }else{
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'message' => mysql_error()
                    )
                );
            }
        }
                
        public function tbExportAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            ini_set("max_execution_time", "180");//避免数据量过大，导出不全的情况出现。
            $filename=date("Y-m-d_H-i-s")."-".$dbname."-".$tbname.".sql";
            header("Content-disposition:filename=".$filename);
            header("Content-type:application/octetstream");
            header("Pragma:no-cache");
            header("Expires:0");

            //备份数据
            $i = 0;
            $crlf="\r\n";
            global $link;
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_select_db($dbname,$link);
            mysql_query("SET NAMES 'utf8'");
//            $tables =mysql_list_tables($dbname,$link);
//            $num_tables = @mysql_numrows($tables);
            print "-- filename=".$filename;
            print $crlf;
            
            global $drop;
            $schema_create = "";
            if(empty($drop)){ 
                $schema_create .= "DROP TABLE IF EXISTS `$tbname`;$crlf";
            }
            $result =mysql_query("SHOW CREATE TABLE $tbname");
            $row=mysql_fetch_array($result);
            $schema_create .= $crlf."-- ".$row[0].$crlf;
            $schema_create .= $row[1];
            echo $schema_create .";$crlf$crlf";
            
            $schema_create = "";
            $temp = "";
            $result = mysql_query("SELECT * FROM $tbname");
            $i = 0;
            while($row = mysql_fetch_row($result)){
                $schema_insert = "INSERT INTO `$tbname` VALUES (";
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
            echo $temp ."$crlf$crlf";
            die;     
        }
        
        public function tbImportAction(){
            $dbname = $this->params()->fromRoute('dbname', '');
            $tbname = $this->params()->fromRoute('tbname', '');
            return new ViewModel(
                array(
                    'dbname' => $dbname,
                    'tbname' => $tbname
                )    
            );
        }
        
        public function tbImportHandleAction(){
            $upload = new Import();
            $upload->setMaxSize(2);
            $importfile = $upload->upload('importfile');
            $data['importfile'] = $importfile;
            $dbname = isset($_POST['dbname'])?$_POST['dbname']:'';
            if($data['importfile']){
                $message = 'file upload success ! ';
                $link = mysql_connect('localhost','root','MySQLSmartTools');
                mysql_query('set names utf8');
                mysql_select_db($dbname,$link);

                $templine = '';
                $lines = file($data['importfile']);
                // Loop through each line
                foreach ($lines as $line)
                {
                    // Skip it if it's a comment
                    if (substr($line, 0, 2) == '--' || $line == '')
                        continue;

                    // Add this line to the current segment
                    $templine .= $line;
                    // If it has a semicolon at the end, it's the end of the query
                    if (substr(trim($line), -1, 1) == ';')
                    {
                        // Perform the query
                        mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
                        // Reset temp variable to empty
                        $templine = '';
                    }
                }
                $message .= 'file imported success ! ';
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'message' => $message
                    )
                );
            }else{
                return new ViewModel(
                    array(
                        'dbname' => $dbname,
                        'message' => 'file upload error' . $upload->getErr()
                    )
                );
            }
        }
        
        public function tbdropAction(){
            $dbname = $_POST['dbname'];
            $tbname  = $_POST['tbname'];
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            mysql_select_db($dbname);
            $sql = 'drop table ' . $tbname; 
            if(mysql_query($sql)){
                    $data = array('status' => 1);
            }else{
                    $data = array('status' => 0);
            }
           echo \json_encode($data);
           die;
        }
       
        public function tbdatalistAction(){
           $dbname = $this->params()->fromRoute('dbname', '');
           $tbname  = $this->params()->fromRoute('tbname', '');
           $link = mysql_connect('localhost', 'root','MySQLSmartTools');
           mysql_query('set name utf8', $link);
           mysql_select_db($dbname);
           $prisql="desc `$tbname`";
           $result1=  mysql_query($prisql);
           while($rowa=  mysql_fetch_array($result1)){
               if($rowa['Key']=='PRI'){
                   $pri=$rowa['Field'];
               }
           }
           $csql="SELECT COLUMN_NAME FROM information_schema.COLUMNS where TABLE_SCHEMA='{$dbname}' and TABLE_NAME='{$tbname}'";
           $re=  mysql_query($csql);
           if(!mysql_query("select id from `$dbname`.`$tbname`")){
                    $sql="select * from `$dbname`.`$tbname`";
           }else{
                    $sql="select * from `$dbname`.`$tbname` order by id desc limit 10000"; 
           }
           $result=  mysql_query($sql);
           return new ViewModel(
                   array(
                        're' => $re,
                        'result' => $result,
                        'dbname' => $dbname,
                        'tbname' => $tbname,
                        'pri'      => $pri,
                    )
            );
        }
       
        public function tbdatadeleteAction(){
            $dbname = $_POST['dbname'];
            $tbname  = $_POST['tbname'];
            $prikey     = $_POST['prikey'];
            $prival      = $_POST['prival'];
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            mysql_select_db($dbname);
            $sql="delete from `$tbname` where  `{$prikey}`='{$prival}'";
            if(mysql_query($sql)){
                    $data = array('status' => 1);
            }else{
                    $data = array('status' => 0);
            }
           echo \json_encode($data);
           die;
        }
        
        public function tbdataemptyAction(){
           $dbname = $_POST['dbname'];
           $tbname  = $_POST['tbname'];
           $link = mysql_connect('localhost', 'root','MySQLSmartTools');
           mysql_query('set name utf8', $link);
           mysql_select_db($dbname);
           $sql="truncate table `$tbname`";
           if(mysql_query($sql)){
                     $data = array('status' => 1);
            }else{
                     $data = array('status' => 0);
            }
           echo \json_encode($data);
           die;
        }
        
        public function tbdataeditAction(){
           $dbname = $this->params()->fromRoute('dbname', '');
           $tbname = $this->params()->fromRoute('tbname', '');
           $prikey = $this->params()->fromRoute('column', '');
           $prival=$this->params()->fromRoute('prival', '');
           $link = mysql_connect('localhost', 'root','MySQLSmartTools');
           mysql_query('set name utf8', $link);
           mysql_select_db($dbname);
           $csql="SELECT COLUMN_NAME FROM information_schema.COLUMNS where TABLE_SCHEMA='$dbname' and TABLE_NAME='$tbname'";
           $re=  mysql_query($csql);
           $sql="select * from `$tbname` where `$prikey`='$prival'";
           $result=  mysql_query($sql);
           return new ViewModel(
                array(
                    're'=>$re,
                    'result' => $result,
                    'dbname'=>$dbname,
                    'tbname'=>$tbname,
                    'prikey'=>$prikey,
                    'prival'=>$prival,
                )
          );
        }
        
        public function tbdataedithandleAction(){
           $dbname = $this->params()->fromRoute('dbname', '');
           $tbname = $this->params()->fromRoute('tbname', '');
           $prikey = $this->params()->fromRoute('column', '');
           $prival=$this->params()->fromRoute('prival', '');
           $link = mysql_connect('localhost', 'root','MySQLSmartTools');
           mysql_query('set name utf8', $link);
           mysql_select_db($dbname);
//           print_r($_POST);
           $sql="update `$dbname`.`$tbname` set ";
           foreach ($_POST as $key => $value) {
               $sql .="`$key`='$value'".",";
           }
           $sql=  substr($sql,0,  strlen($sql)-12);
           $sql .=" where `$prikey`='$prival'";
//           echo $sql;
           if(mysql_query($sql)){
               return $this->redirect()->toRoute('database', array('action' => 'tbdatalist', 'dbname' => $dbname,'tbname'=>$tbname));
           }else{
               return new ViewModel(
                array(
                    'error' =>mysql_error(),
                )
          );
           }
        }
        
        public function tbdataaddAction(){
           $dbname = $this->params()->fromRoute('dbname', '');
           $tbname = $this->params()->fromRoute('tbname', '');
           $link = mysql_connect('localhost', 'root','MySQLSmartTools');
           mysql_query('set name utf8', $link);
           mysql_select_db($dbname);
           $csql="SELECT COLUMN_NAME FROM information_schema.COLUMNS where TABLE_SCHEMA='$dbname' and TABLE_NAME='$tbname'";
           $result=  mysql_query($csql);
           return new ViewModel(
                array(
                    'result' => $result,
                    'dbname'=>$dbname,
                    'tbname'=>$tbname,
                )
          );
        }
        
        public function tbdataaddhandleAction(){
           $dbname = $this->params()->fromRoute('dbname', '');
           $tbname = $this->params()->fromRoute('tbname', '');
           $link = mysql_connect('localhost', 'root','MySQLSmartTools');
           mysql_query('set name utf8', $link);
           mysql_select_db($dbname);
           $sql="insert into `$dbname`.`$tbname` values(";
           foreach ($_POST as $value) {
               $sql .="'$value'".",";
           }
           $sql=  substr($sql,0,  strlen($sql)-6);
           $sql .=")";
//           echo $sql;
           if(mysql_query($sql)){
               return $this->redirect()->toRoute('database', array('action' => 'tbdatalist', 'dbname' => $dbname,'tbname'=>$tbname));
           }else{
               return new ViewModel(
                array(
                    'error' => mysql_error(),
                    'dbname'=>$dbname,
                    'tbname'=>$tbname,
                )
               );
           }
        }
    } 