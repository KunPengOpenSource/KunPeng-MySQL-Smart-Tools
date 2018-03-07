<?php
namespace Operation;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

//use Operation\Model\export;
use Operation\Model\Operation;
use Operation\Model\OperationTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway; 
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig(){
        return array(
            'factories' => array(
                'Operation\Model\OperationTable' => function($sm){
                   $tableGateway = $sm->get('OperationTableGateway');
                   $table = new OperationTable($tableGateway);
                   return $table;
                },
                'OperationTableGateway' => function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Operation());
                    return new TableGateway('operation', $dbAdapter,null,$resultSetPrototype);
                },
            ),
        );
    }
}
?>
