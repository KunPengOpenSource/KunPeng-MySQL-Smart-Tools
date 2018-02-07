<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Operation\Model\Operation;

class UserController extends AbstractActionController
{
    protected $operationTable;
    public function indexAction()
    {
       $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db('mysql',$link);  
        $sql = 'select * from user';
        $result = mysql_query($sql);
        return new ViewModel(
            array(
                'result' => $result,
            )
        );
    }
    
    public function createUserAction(){}
    
    public function createUserHandleAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");       
        $request = $this->getRequest();
        if($request->isPost()){
            //应该判断若需建用户已存在是否删除已有的同名用户重新建立用户
            $re=mysql_query("select * from user WHERE User='{$_POST['username']}' AND Host='{$_POST['host']}'");
            if(mysql_fetch_array($re)){
                return new ViewModel(
                    array(
                        'mess' =>"The user '{$_POST['username']}'@'{$_POST['host']}' already exists!" ,
                    )
                );
            }
//            $dsql="DELETE FROM user WHERE User='{$_POST['username']}' AND Host='{$_POST['host']}'";
//            mysql_query($dsql);
            $arr=$_POST['checkbox'];
//            print_r($arr);
            $priv=implode(',',$arr );
//            echo $priv;
//            var_dump($priv);
//            $str1="select,insert,update,delete,file,create,alter,index,drop,create temporary tables,show view,create routine,alter routine,execute,create view,event,trigger,grant,super,process,reload,shutdown,show databases,lock tables,references,replication client,replication slave,create user";
//            $str2="select,insert,update,delete,file,create,alter,index,drop,create temporary tables,show view,create routine,alter routine,execute,create view,event,trigger,super,process,reload,shutdown,show databases,lock tables,references,replication client,replication slave,create user";
//            if($priv==$str1||$priv==$str2){
//                $apriv='ALL PRIVILEGES ';
//            }else{
//                $apriv=$priv;
//            }
//            echo 'APRIV='.$apriv.'<br>';
            $grant=(stripos($priv, 'grant')!=FALSE)?"WITH GRANT OPTION":null;
            $ppriv=  str_replace("grant,", "", $priv);
//           $sql1 ="CREATE USER '{$_POST['username']}'@'{$_POST['host']}' IDENTIFIED BY '{$_POST['password']}';";
            $sql2 ="GRANT $ppriv ON *.* TO '{$_POST['username']}'@'{$_POST['host']}' IDENTIFIED BY '{$_POST['password']}' $grant;";
            $sql3 ="flush privileges;";
//            echo $sql2;
//            $row1=mysql_query($sql1);
            $row2=mysql_query($sql2);
            $row3=mysql_query($sql3);
//            var_dump($row1);
//            var_dump($row2);
//            var_dump($row3);
            if($row2 && $row3){
                return $this->redirect()->toRoute('user', array('action' => 'index'));
            }
            return $this->redirect()->toRoute('user', array('action' => 'createuser'));
        }
    }
    
    public function deleteUserAction(){
        $name = $_POST['str1'];
        $host = $_POST['str2'];
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $sql="DELETE FROM user WHERE User='".$name."' AND Host='".$host."'";
        if(mysql_query($sql)){
            $data = array('status' => 1);
        }else{
            $data = array('status' => 0);
        }
        echo \json_encode($data);
        die;
    }
    
    public function editUserAction()
   {
        $name = $this->params()->fromRoute('name', '');
        $host = $this->params()->fromRoute('host', '');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $sql="select * from user where user='$name' and host='$host'";
        $result=mysql_query($sql);
        if($result){
            $row=  mysql_fetch_row($result);
            return new ViewModel(
                array(
                    'row' => $row,
                )
            );
        }else{
            $mess = "Error ".\mysql_errno().":".\mysql_error();
            return new ViewModel(
                array(
                    'mess' => $mess,
                )
            );
        }
    }
     
    public function changelogininfoAction(){
        $name = $this->params()->fromRoute('name', '');
        $host = $this->params()->fromRoute('host', '');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $request = $this->getRequest();
        if($request->isPost()){
            $sql="update mysql.user set User='{$_POST['username']}',Host='{$_POST['host']}' where User='$name' and Host='$host'";
//            echo $sql;
            if(mysql_query($sql))
                return $this->redirect()->toRoute('user', array('action' => 'index'));
            else
                return new ViewModel(
                    array(
                        'mess' =>  "Error ".\mysql_errno().":".\mysql_error(),
                    )
                );
        }
    }
    
    public function changepassAction(){
        $name = $this->params()->fromRoute('name', '');
        $host = $this->params()->fromRoute('host', '');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $request = $this->getRequest();
        if($request->isPost()){
            $sql1="UPDATE mysql.user SET password=PASSWORD('{$_POST['password']}') WHERE User='$name' and Host='$host'";
            $sql2="FLUSH PRIVILEGES";
            $r1=mysql_query($sql1);
            $r2=mysql_query($sql2);
            if($r1&&$r2){
                return $this->redirect()->toRoute('user', array('action' => 'index'));
            }else{
                return new ViewModel(
                    array(
                        'mess' =>  mysql_error() ,
                    )
                );
            }
        }
    }
    
    public function changeprivAction(){
        $name = $this->params()->fromRoute('name', '');
        $host = $this->params()->fromRoute('host', '');
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $sql1="REVOKE ALL PRIVILEGES ON *.* FROM '$name'@'$host'";
//        echo $sql1;
        $l=mysql_query($sql1);
//        var_dump($l);
        $re=mysql_query("select * from mysql.user where User='$name' and Host='$host'");
        $row=  mysql_fetch_row($re);
//        print_r($row);
        if($row[13]=='Y'){
            mysql_query("REVOKE grant option ON *.* FROM '$name'@'$host'");
        }
        $request = $this->getRequest();
        if($request->isPost()){
            if(isset($_POST['checkbox'])){
                $arr=$_POST['checkbox'];
                $priv=implode(',',$arr );
                $grant=(stripos($priv, 'grant')!=FALSE)?"WITH GRANT OPTION":null;
                $ppriv=  str_replace("grant,", "", $priv); 
                $sql2 ="GRANT $ppriv ON *.* TO '$name'@'$host' $grant";
                $row2=mysql_query($sql2);
                if($row2){
                    return $this->redirect()->toRoute('user', array('action' => 'index'));
                }else{
                    return new ViewModel(
                        array(
                            'mess' =>  mysql_error()
                        )
                    );
                }
            }else{
                return $this->redirect()->toRoute('user', array('action' => 'index'));
            }
        }
    }
}
?>




