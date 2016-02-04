<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use CleanPhp\Invoicer\Persistence\Zend\TableGateway\TableGatewayFactory;
use CleanPhp\Invoicer\Persistence\Zend\DataTable\CustomerTable;
use CleanPhp\Invoicer\Persistence\Zend\DataTable\InvoiceTable;
use CleanPhp\Invoicer\Persistence\Zend\DataTable\OrderTable;
use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use Zend\Stdlib\Hydrator\ClassMethods;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use CleanPhp\Invoicer\Persistence\Hydrator\InvoiceHydrator;

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'OrderHydrator' => function($sm) {
                return new OrderHydrator(
                    new ClassMethods(),
                    $sm->get('CustomerTable')
                );
            },
            'InvoiceHydrator' => function($sm) {
                return new InvoiceHydrator(
                    new ClassMethods(),
                    $sm->get('OrderTable')
                );
            },
            'CustomerTable' => function($sm) {
                $factory = new TableGatewayFactory();
                $hydrator = new ClassMethods();

                return new CustomerTable(
                    $factory->createGateway(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $hydrator,
                        new Customer(),
                        'customers'
                    ),
                    $hydrator
                );
            },
            'OrderTable' => function($sm) {
                $factory = new TableGatewayFactory();
                $hydrator = $sm->get('OrderHydrator');

                return new OrderTable(
                    $factory->createGateway(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $hydrator,
                        new Order(),
                        'orders'
                    ),
                    $hydrator
                );
            },
            'InvoiceTable' => function($sm) {
                $factory = new TableGatewayFactory();
                $hydrator = $sm->get('InvoiceHydrator');

                return new InvoiceTable(
                    $factory->createGateway(
                        $sm->get('Zend\Db\Adapter\Adapter'),
                        $hydrator,
                        new Invoice(),
                        'invoices'
                    ),
                    $hydrator
                );
            },
        ),
    ),
);
