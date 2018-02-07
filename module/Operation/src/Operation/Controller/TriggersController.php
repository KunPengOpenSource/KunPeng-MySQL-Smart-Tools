<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Operation\Model\Operation;

class TriggersController extends AbstractActionController
{
    protected $operationTable;
    public function indexAction()
    {
       $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
//        mysql_select_db('cartdb',$link);  
        $sql = 'SELECT * FROM information_schema.`TRIGGERS`';
        $result = mysql_query($sql);
        return new ViewModel(
            array(
                'result' => $result,
            )
        );
    }
    
    public function createTriggerAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $sql="show databases";
        $result=  mysql_query($sql);
        while( $row = mysql_fetch_array($result) )
        {
           $dbname[] = $row;
        }
//        $dbname = $this->params()->fromRoute('dbname', '');
//        if(isset($dbname)){ 
//            mysql_select_db($dbname);
//            $q=mysql_query("show tables"); 
//            while($row=mysql_fetch_array($q)){ 
//                $select[] = $row[0]; 
//            } 
//            $tbname=$select;
//        }
        $sql2 = "select * from `information_schema`.`TABLES` order by 'TABLE_SCHEMA' asc";
        if( !($result2 = mysql_query($sql2)) )
        {
           die('Could not query tables list');
        }

        $tbname = array();
        while( $row2 = mysql_fetch_array($result2) )
        {
           $tbname[] = $row2;
        }
    //    print_r( $forum_data2);
        mysql_free_result($result2);
        return new ViewModel(
            array(
                'dbname' => $dbname,
                'tbname'=>$tbname,
            )
        );
    }
    
    public function createTriggerHandleAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
//        $dbname=$_POST['databaseName'];
//        mysql_select_db("$dbname");
//        $name = $this->params()->fromRoute('name', '');
//        $dsql="DROP TRIGGER IF EXISTS `$name`";
//        mysql_query($dsql);
        $request = $this->getRequest();
        if($request->isPost()){
            mysql_select_db($_POST['databaseName']);
//            $dsql="DROP TRIGGER `{$_POST['triggerName']}`";
//            if(mysql_query($dsql)){
            $sql ="CREATE DEFINER = {$_POST['user']} TRIGGER `".(isset($_POST['triggerName'])?$_POST['triggerName']:'')."` {$_POST['timing']} {$_POST['event']} ON `".$_POST['table']."` FOR EACH ROW {$_POST['statement']}";
            $row=mysql_query($sql, $link);
            if($row){
                return $this->redirect()->toRoute('triggers', array('action' => 'index'));
            }else{
                 return new ViewModel(
                    array(
                        'error' => mysql_error(),
                    )
                 );
            }
           
//                return $this->redirect()->toRoute('triggers', array('action' => 'createtrigger'));
        }
        //return $this->redirect()->toRoute('triggers', array('action' => 'createtrigger'));
    }
    
    public function deleteTriggerAction(){
        $name = $_POST['str1'];
        $dbname=$_POST['str2'];
        $sql = 'drop trigger `' . $name . '`';
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("$dbname");
        if(mysql_query($sql)){
            $data = array('status' => 1);
        }else{
            $data = array('status' => 0);
        }
        echo \json_encode($data);
        die;
    }
    
    public function editTriggerAction()
   {
        $name = $this->params()->fromRoute('name', '');
        $dbname = $this->params()->fromRoute('dbname', '');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db($dbname);
        $sql="show create trigger `$name`";
        $result=mysql_query($sql);
//        $numsql="SELECT count( * ) FROM information_schema.tables WHERE TABLE_SCHEMA = `$dbname`";
//        $num=  mysql_query($numsql);
        $tbnamesql="show tables;";
        $tbname=  mysql_query($tbnamesql);
        return new ViewModel(
            array(
                'result' => $result,
                'tbname'=>$tbname,
                'dbname'=>$dbname,
            )
        );
    }
    
    public function edittriggerhandleAction(){
        $name = $this->params()->fromRoute('name', '');
        $dbname = $this->params()->fromRoute('dbname', '');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db($dbname);
        $sql="show create trigger `$name`";
        $result=  mysql_query($sql);
        $row=  mysql_fetch_array($result);
//        print_r($row);
        $request = $this->getRequest();
        if($request->isPost()){
            $sql1="DROP TRIGGER IF EXISTS `$name`";
            mysql_query($sql1);
            $sql2="CREATE DEFINER = {$_POST['user']} TRIGGER `{$_POST['triggerName']}` {$_POST['timing']} {$_POST['event']} ON `{$_POST['table']}` FOR EACH ROW {$_POST['statement']}";
//            echo $sql2;
            if(mysql_query($sql2)){
                return $this->redirect()->toRoute('triggers', array('action' => 'index'));
            }else{
                $error=mysql_error();
                mysql_query($row[2]);  
                return new ViewModel(
                    array(
                        'error' =>$error,
                    )
                );
           } 
        }
    }

    public function getOperationTable()
    {
        if (!$this->operationTable) {
            $sm = $this->getServiceLocator();
            $this->operationTable = $sm->get('Operation\Model\OperationTable');
        }
        return $this->operationTable;
    }
}
?>
