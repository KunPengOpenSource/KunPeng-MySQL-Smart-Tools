<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MysqlCfgController extends AbstractActionController
{
    protected $operationTable;
    public function indexAction() {
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db('MySQL-Smart-Tools-DB');
        $var=explode(",","hostname,socket,port,version,version_compile_os,storage_engine,collation_connection,basedir,datadir,plugin_dir,tmpdir,log_error,general_log_file,slow_query_log");
//        print_r($var);
        foreach($var as $value){
            $sql="show variables like '$value';";
//            echo $sql;
            $result = mysql_query($sql);
//            var_dump($result);
            while($arr=  mysql_fetch_array($result)){
                $val[]=$arr[1];
//                print_r($val);
            }
        }
        $result1 = mysql_query("show status like 'uptime';");
//        var_dump($result1);
        while($row = mysql_fetch_row($result1)){
//            print_r($row);
            $seconds = $row[1];
            $s = $seconds % 60;
            $m = $seconds/60 % 60;
            $h = $seconds/3600 % 24;
            $d = floor(($seconds/3600)/24);
            $uptime=$d . ' days ' . $h . ' hours ' . $m . ' minutes ' . $s .' seconds ';
         }
        return new ViewModel(
            array(
                'val' => $val,
                'uptime'=>$uptime,
            )
        );
    }
    
    public function userInfoAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $re= mysql_query('select * from user');
//        print_r($row);
        return new ViewModel(
            array(
                're' => $re,
            )
        );
    }
    public function clientConnAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $result = mysql_query("show processlist;");
        return new ViewModel(
                array(
                    'result' => $result,
                )
        );
    }
    
    public function killProcesslistAction(){
         $id=$_POST['id'];
         $link = mysql_connect('localhost', 'root','MySQLSmartTools');
         mysql_query('set name utf8', $link);
         if(isset($id)){
            mysql_query("kill {$id}");
            $data = array(
                'status' => 1
            );
         }else{
            $data = array(
                'status' => 0
            );
         } 
        echo \json_encode($data);
        die;
    }
    
    public function showVarsAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $rs = mysql_query('show variables');
        return new ViewModel(
            array(
                'rs' => $rs,
            )
        );
    }
    
    public function functAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        if(isset($_POST['x'])&&isset($_POST['y'])&&isset($_POST['z'])){
            $x=$_POST['x'];
            $y=$_POST['y'];
            $z=$_POST['z'];
            if(!$link){
                echo 'Could not connect:'.mysql_errno();
            }else{
                if($z=="c"){
                    $sql='set global '.$x."= ".$y;
                    $ret=  mysql_query($sql);
                    echo $y;
                    die;
                }
                if($z=="a"){
                    $sql='set '.$x."= ".$y;
                    $ret=  mysql_query($sql);
                    echo $y;
                    die;
                }
                if($z=="b"){
                    $newsql='SELECT * FROM `information_schema`.GLOBAL_VARIABLES where VARIABLE_NAME="'.$x.'"';
                    $result=  mysql_query($newsql);
                    $row=  mysql_fetch_row($result);
                    $re = $row[1];
                    $sql= 'set global '.$x."= ".$re;
                    mysql_query($sql);
                    echo $re;
                    die;
                }
            }
        }
    }
    
    public function showStatusAction(){
       $link = mysql_connect('localhost', 'root','MySQLSmartTools');
       mysql_query('set name utf8', $link);
       mysql_select_db("mysql");
       $result=  mysql_query("show global status");
       return new ViewModel(
                array(
                    'result' => $result,
                )
        );
    }
    public function loginRecordAction(){
       
    }
    
    public function slowQueryAction(){
        //获取慢查询分析结�?
        $command  ="cat /var/www/html/MSTV2.0/public/slowquery.txt |sed '/0users@0hosts/d' | awk '{print $0}'";
        exec($command, $output);
        if($output !=NULL){ 
            $warning="Warning: Please check the slow_query_log is open or not!";
            if("$output[0]"==$warning){
                return new ViewModel(
                    array(
                        'warning' => $warning,
                    )
                );
            }
        //取得记录总数，计算总页数用
        $numrows= count($output);
        //设定每一页显示的记录�?
        $pagesize=12;
        //计算总页�?
        $pages=  ceil($numrows/$pagesize);
        //获取页码
        $page=$this->params()->fromRoute('id', '1');
        //判断页码是否合法
        if($page <0 || $page > $pages){
            return $this->redirect()->toRoute('mysqlcfg', array('action' => 'slowQuery'));
        }
        //计算记录起始偏移�?
        $offset=$pagesize*($page-1);
        //计算记录结束偏移�?
        $end=($offset==$numrows-$numrows%$pagesize)?($offset+$numrows%$pagesize):($offset+$pagesize);
        //初始化页码显示个�?
        $page_len=7;
        $max_p=$pages;
        $page_len=$page_len%2?$page_len:$page_len+1;
        //页码左右偏移�?
        $pageoffset=($page_len-1)/2;
        if($pages > $page_len){
            //如果当前页小于等于左偏移
            if($page <= $pageoffset){
                $init = 1;
                $max_p = $page_len;
            }else{
                 ////如果当前页大于左偏移
                //如果当前页码右偏移超出最大分页数
                if($page + $pageoffset >= $pages + 1){
                    $init = $pages - $page_len + 1;
                }else{
                    //左右偏移都存在时的计�?
                    $init = $page - $pageoffset;
                    $max_p = $page + $pageoffset;
                }
            }
        }else{
            $init = 1;
            $max_p = $pages;
        }
        }
        return new ViewModel(
            array(
                'offset' => $offset,
                'pages'   => $pages,
                'end'  =>$end,
                'output'    =>$output,
                'init'        =>$init,
                'max_p'      =>$max_p,
            )
        );
    }
    
    public function addUserAction(){}
    
    public function addUserHandleAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");       
        $request = $this->getRequest();
        if($request->isPost()){
            //应该判断若需建用户已存在是否删除已有的同名用户重新建立用�?
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
                return $this->redirect()->toRoute('mysqlcfg', array('action' => 'userInfo'));
            }
            return $this->redirect()->toRoute('mysqlcfg', array('action' => 'addUser'));
        }
    }
    
    public function delUserAction(){
        $name = $_POST['str2'];
        $host = $_POST['str1'];
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        mysql_select_db("mysql");
        $sql="DELETE FROM user WHERE User='$name' AND Host='$host'";
        if(mysql_query($sql)){
            $data = array('status' => 1);
        }else{
            $data = array('status' => 0);
        } 
        echo \json_encode($data);
        die;
    }
    public function moreAction(){
        if(isset($_POST['username'])&&isset($_POST['host'])){
            $username = $_POST['username'];
            $host = $_POST['host'];
            $link = mysql_connect('localhost', 'root','MySQLSmartTools');
            mysql_query('set name utf8', $link);
            mysql_select_db("mysql");
            $sql = 'select * from db where user="' . $username . '" and host="' .$host.'"';
            $rs = mysql_query($sql);
            $i=1;
            $str = '';
            while ($row = mysql_fetch_row($rs)) {
                $str .= "<li class='child' id='child{$i}'>";
                $str .= "<table><tr><td colspan='3'><div class='dbname'>{$row[1]}</div></td></tr><tr id='ch'>";
                $str .= "<td><ul><li>Object Rights:&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[3]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='select' />&nbsp;Select&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[4]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='insert' />&nbsp;Insert&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[5]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='update' />&nbsp;Update&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[6]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='delete' />&nbsp;Delete&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[19]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='execute' />&nbsp;Execute&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[16]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='showview' />&nbsp;Show View&nbsp;</li></ul></td> ";
                $str .= "<td><ul><li>DDL Rights:&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[7]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='create' />&nbsp;Create&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[12]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='alter' />&nbsp;Alter&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[10]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='reference' />&nbsp;Reference&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[11]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='index' />&nbsp;Index&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[15]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='createview' />&nbsp;Create View&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[17]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='createroutine' />&nbsp;Create Routine&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[18]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='alterroutine' />&nbsp;Alter Routine&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[20]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='event' />&nbsp;Event&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[8]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='drop' />&nbsp;Drop&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[21]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='trigger' />&nbsp;Trigger&nbsp;</li></ul></td>";
                $str .= "<td><ul><li>Others Rights:&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[3]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='grantoption' />&nbsp;Grant Option&nbsp;</li>";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[6]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='createtemporary' />&nbsp;Create Temporary&nbsp;</li> ";
                $str .= "<li><input type='checkbox' name='ObjectR'";
                $check = $row[16]=="Y" ? "checked" : "";
                $str .= $check . " disabled='true' value='locktables' />&nbsp;Lock Tables&nbsp;</li></ul></td></tr></table></li>";
                $i++;
            } 
            $arr = array(
                'str' => $str,
                'status' => 1
            );
            echo json_encode($arr);
            die;
        }
    }
}
