<?php
namespace Operation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ViewsController extends AbstractActionController
{
    protected $operationTable;
    public function indexAction()
    {
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $sql = 'SELECT * FROM information_schema.views';
        $result = mysql_query($sql);
        return new ViewModel(
            array(
                'result' => $result,
            )
        );
    }
    
    public function createViewAction(){}
    
    public function createViewHandleAction(){
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $request = $this->getRequest();
        if($request->isPost()){
            mysql_select_db($_POST['dbname']);
            $sql = 'CREATE ' . (isset($_POST['orreplace'])?$_POST['orreplace']:'') . ' algorithm = ' . $_POST['algorithm'] . ' DEFINER = ' . $_POST['user'] . ' SQL SECURITY ' . $_POST['sqlsec']  . ' VIEW `' . $_POST['viewname'] . '`';
            $sql .= empty($_POST['columnname']) ? '': '(' . $_POST['columnname'] . ')'; 
            $sql .= ' AS ' . $_POST['select_statement'];
            $sql .= !empty($_POST['check'])? ' WITH ' . $_POST['check'] . ' CHECK OPTION' : '';
            if(mysql_query($sql)){
                return $this->redirect()->toRoute('views', array('action' => 'index'));
            }else{
                return new ViewModel(
                    array('error' => mysql_error())
                ); 
            }
        }
        return true;
    }
    
    public function deleteViewAction(){
        $name = $_POST['str2'];
        $dbname = $_POST['str1'];
        $sql = 'drop view ' . $dbname . '.' . $name;
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        if(mysql_query($sql)){
            $data = array('status' => 1);
        }else{
            $data = array('status' => 0);
        }
        echo \json_encode($data);
        die;
    }
    
    public function editViewAction()
    {
        $link = mysql_connect('localhost', 'root','MySQLSmartTools');
        mysql_query('set name utf8', $link);
        $name = $this->params()->fromRoute('name', '');
        $dbname = $this->params()->fromRoute('dbname', '');
        $sql = 'select * from information_schema.views where TABLE_SCHEMA=\'' . $dbname . '\' and TABLE_NAME=\'' . $name . '\'';
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
        return new ViewModel(
            array(
                'row' => $row,
            )
        );
        
//        echo "This is editAction";
//        $id = (int) $this->params()->fromRoute('id', 0);
//        if(!$id){
//            return $this->redirect()->toRoute('views', array(
//                'action' => 'add',
//            ));
//        }
//
//        try{
//            $operation = $this->getOperationTable()->getOperation($id);
//        }
//        catch(\Exception $ex){
//            return $this->redirect()->toRoute('views', array(
//                'action' => 'index'
//            ));
//        }
//
//        $form = new operationForm();
//        $form->bind($operation);
//        $form->get('submit')->setAttribute('value', 'Edit');
//
//        $request = $this->getRequest();
//        if($request->isPost()){
//            $form->setInputFilter($operation->getInputFilter());
//            $form->setData($request->getPost());
//
//            if($form->isValid()){
//                $this->getOperationTable()->saveOperation($operation);
//                return $this->redirect()->toRoute('views');
//            }
//        }
//
//        return array(
//            'id' => $id,
//            'form' => $form,
//        );
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
