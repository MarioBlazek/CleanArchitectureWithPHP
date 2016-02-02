<?php

use CleanPhp\Invoicer\Domain\Entity\Order;
use Zend\Stdlib\Hydrator\ClassMethods;
use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;

describe('Persistence\Hydrator\OrderHydrator', function() {
    beforeEach(function() {
        $this->repository = $this->getProphet()->prophesize(
            'CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface'
        );

        $this->hydrator = new OrderHydrator(
            new ClassMethods(),
            $this->repository->reveal()
        );
    });

    describe('->hydrate()', function() {
        it('should perform basic hydration of attributes', function() {
            $data = [
                'id'            => 100,
                'order_number'  => '20150101-019',
                'description'   => 'simple order',
                'total'         => 500,
            ];

            $order = new Order();
            $this->hydrator->hydrate($data, $order);

            expect($order->getId())->to->equal(100);
            expect($order->getOrderNumber())->to->equal('20150101-019');
            expect($order->getDescription())->to->equal('simple order');
            expect($order->getTotal())->to->equal(500);
        });

        it('should hydrate a Customer entity on the Order', function() {
            $data = [
                'customer_id' => 500,
            ];

            $customer = (new Customer())->setId(500);
            $order = new Order();

            $this->repository->getById(500)
                ->shouldBeCalled()
                ->willReturn($customer);

            $this->hydrator->hydrate($data, $order);

            expect($order->getCustomer())->to->equal($customer);

            $this->getProphet()->checkPredictions();
        });
    });
});
