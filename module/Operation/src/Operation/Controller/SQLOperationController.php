<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SQLOperationController extends AbstractActionController
{
    public function indexAction(){}

    public function sqlHandleAction(){
        $link = mysql_pconnect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $request = $this->getRequest();
        if($request->isPost()){
            $sql = $_POST['data'];
            $sqlarr=  explode(";",rtrim($sql,";"));
            $db = mysql_query('select database()');
            $db = mysql_result($db,0);
            mysql_select_db($db);
            for($i=0;$i<count($sqlarr);$i++){
                $result = mysql_query($sqlarr[$i]);
            }
            if(!$result){
                return new ViewModel(
                    array(
                        'error' => "Error ".  mysql_errno().": ".mysql_error(),
                    )
                );
            }elseif($result=='true'){
                return new ViewModel(
                    array(
                        'result' => $result,
                        'msg' => 'This is success',
                    )
                );
            }else{
                return new ViewModel(
                    array(
                        'result' => $result,
                    )
                );
            }
        }
    }
}
?>
