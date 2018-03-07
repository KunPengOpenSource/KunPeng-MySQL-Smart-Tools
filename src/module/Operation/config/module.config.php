<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Operation\Controller\Index' => 'Operation\Controller\IndexController',
            'Operation\Controller\DataBase' => 'Operation\Controller\DataBaseController',
            'Operation\Controller\Views' => 'Operation\Controller\ViewsController',
            'Operation\Controller\Triggers' => 'Operation\Controller\TriggersController',
            'Operation\Controller\User' => 'Operation\Controller\UserController',
            'Operation\Controller\SQLOperation' => 'Operation\Controller\SQLOperationController',
            'Operation\Controller\Analysis' => 'Operation\Controller\AnalysisController',
            'Operation\Controller\MysqlCfg' => 'Operation\Controller\MysqlCfgController',
            'Operation\Controller\LinuxStatus' => 'Operation\Controller\LinuxStatusController',
            'Operation\Controller\About' => 'Operation\Controller\AboutController',
        ),
    ),
    
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Operation\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
             'about' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/about',
                    'defaults' => array(
                        'controller' => 'Operation\Controller\About',
                        'action'     => 'index',
                    ),
                ),
            ),
            'database' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/database[/][:action][/:dbname][/:tbname][/:column][/:prival]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'dbname'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'tbname'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'column'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'prival'   => '[a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\DataBase',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'views' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/views[/][:action][/:id][/:name][/:dbname]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'name'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\Views',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'sqloperation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/sqloperation[/][:action][/:id][/:name][/:dbname]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'name'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\SQLOperation',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'triggers' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/triggers[/][:action][/:name][/:dbname]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'name'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'dbname'=> '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\triggers',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user[/][:action][/:id][/:name][/:host]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'name'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'host'   => '[%a-zA-Z0-9_\.:-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'analysis' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/analysis[/][:action][/:specification]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'specification' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\Analysis',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'linuxstatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/linuxstatus[/][:action][/:id][/:name][/:host]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'name'   => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'host'   => '[a-zA-Z0-9_\.:-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\LinuxStatus',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'mysqlcfg' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mysqlcfg[/][:action][/:id][/:size]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'size'   => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Operation\Controller\MysqlCfg',
                        'action'     => 'index',
                    ),
                ),
            ),
            
        ),
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'views' => __DIR__ . '/../view',
            'sqloperation' => __DIR__ . '/../view',
            'triggers' => __DIR__ . '/../view',
            'user' => __DIR__ . '/../view',
            'database' => __DIR__ . '/../view',
            'analysis' => __DIR__ . '/../view',
            'linuxstatus' => __DIR__ . '/../view',
        ),
    ),
    
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    
);