<?php

use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Service\InputFilter\OrderInputFilter;

describe('InputFilter\Order', function() {
    beforeEach(function() {
        $this->inputFilter = new OrderInputFilter();
    });

    describe('->isValid()', function() {
        it('should require a customer id', function() {
            $isValid = $this->inputFilter->isValid();

            $error = [
                'id' => [
                    'isEmpty' => 'Value is required and can\'t be empty'
                ]
            ];

            $customer = $this->inputFilter->getMessages()['customer'];

            expect($isValid)->to->equal(false);
            expect($customer)->to->equal($error);
        });
    });

    it('should require an order number', function () {
        $isValid = $this->inputFilter->isValid();

        $error = [
            'isEmpty' => 'Value is required and can\'t be empty'
        ];

        $orderNo = $this->inputFilter->getMessages()['orderNumber'];

        expect($isValid)->to->equal(false);
        expect($orderNo)->to->equal($error);
    });

    it('should require order numbers be 13 chars long', function () {

        $scenarios = [
        [
            'value' => '124',
            'errors' => [
                'stringLengthTooShort' =>
                    'The input is less than 13 characters long'
            ] ],
        [
            'value' => '20001020-0123XR',
            'errors' => [
                'stringLengthTooLong' =>
                    'The input is more than 13 characters long'
            ] ],
        [
            'value' => '20040717-1841', 'errors' => null
        ] ];

        foreach ($scenarios as $scenario) {

            $this->inputFilter = new OrderInputFilter();

            $this->inputFilter->setData([
                'orderNumber' => $scenario['value']
            ])->isValid();

            $messages = $this->inputFilter->getMessages()['orderNumber'];

            expect($messages)->to->equal($scenario['errors']);
        }
    });

    it('should require a description', function () { $isValid = $this->inputFilter->isValid();
        $error = [
            'isEmpty' => 'Value is required and can\'t be empty'
        ];

        $messages = $this->inputFilter->getMessages()['description'];

        expect($isValid)->to->equal(false);
        expect($messages)->to->equal($error);
    });

    it('should require a total', function () { $isValid = $this->inputFilter->isValid();
        $error = [
            'isEmpty' => 'Value is required and can\'t be empty'
        ];

        $messages = $this->inputFilter->getMessages()['total'];

        expect($isValid)->to->equal(false);
        expect($messages)->to->equal($error);
    });

    it('should require total to be a float value', function () {

        $scenarios = [
        [
            'value' => 124, 'errors' => null
        ], [
            'value' => 'asdf',
            'errors' => [
                'notFloat'
                => 'The input does not appear to be a float'
            ] ],
        [
            'value' => 99.99, 'errors' => null
        ] ];

        foreach ($scenarios as $scenario) {

            $this->inputFilter = new OrderInputFilter();

            $this->inputFilter->setData([
                'total' => $scenario['value']
            ])->isValid();

            $messages = $this->inputFilter->getMessages()['total'];

            expect($messages)->to->equal($scenario['errors']);
        }
    });

    it('should hydrate the embedded customer data', function () {

        $data = [
            'customer' => ['id' => 20]
        ];

        $order = new Order();

        $this->hydrator->hydrate($data, $order);

        assert(
            $data['customer']['id'] === $order->getCustomer()->getId(),
            'id does not match'
        );
    });

    it('should extract the customer object', function () {

        $order = new Order();

        $order->setCustomer((new Customer())->setId(14));

        $data = $this->hydrator->extract($order);

        assert(
            $order->getCustomer()->getId() === $data['customer_id'],
          'customer_id is not correct'
        );
    });
});
