<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'view_helpers' => array(
        'invokables' => array(
            'validationErrors' => 'Application\View\Helper\ValidationErrors',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'customers' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/customers',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Customers',
                        'action'    => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'new' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/new',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'new-or-edit',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit/:id',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'new-or-edit',
                            ),
                        ),
                    ),
//                    'create' => array(
//                        'type' => 'Segment',
//                        'options' => array(
//                            'route' => '/new',
//                            'defaults' => array(
//                                'action' => 'new',
//                            ),
//                        ),
//                    ),
                ),
            ),
            'orders' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/orders[/:action[/:id]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Orders',
                        'action'    => 'index',
                    ),
                ),
            ),
            'invoices' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/invoices[/:action[/:id]]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Invoices',
                        'action'    => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
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
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        ),
        'factories' => array(
            'Application\Controller\Customers' => function($sm) {
                return new \Application\Controller\CustomersController(
                    $sm->getServiceLocator()->get('CustomerRepository'),
                    new \CleanPhp\Invoicer\Service\InputFilter\CustomerInputFilter(),
                    new \Zend\Stdlib\Hydrator\ClassMethods()
                );
            },
            'Application\Controller\Orders' => function($sm) {
                return new \Application\Controller\OrdersController(
                    $sm->getServiceLocator()->get('OrderRepository'),
                    $sm->getServiceLocator()->get('CustomerRepository'),
                    new \CleanPhp\Invoicer\Service\InputFilter\OrderInputFilter(),
                    $sm->getServiceLocator()->get('OrderHydrator')
                );
            },
            'Application\Controller\InvoicesController' => function($sm) {
                return new \Application\Controller\InvoicesController(
                    $sm->getServiceLocator()->get('InvoiceRepository'),
                    $sm->getServiceLocator()->get('OrderRepository'),
                    new \CleanPhp\Invoicer\Domain\Service\InvoicingService(
                        $sm->getServiceLocatot()->get('OrderRepository'),
                        new \CleanPhp\Invoicer\Domain\Factory\InvoiceFactory()
                    )
                );
            },
        ),
    ),
//    'view_helpers' => array(
//        'invokables' => array(
//            'validationErrors' => 'Application\View\Helper\ValidationErrors',
//        ),
//    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
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
