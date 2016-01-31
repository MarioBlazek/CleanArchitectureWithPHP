<?php

namespace CleanPhp\Invoicer\Domain\Service;

use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;

class InvoicingService
{
    protected $orderRepository;

    protected $invoiceFactory;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceFactory $invoiceFactory
    )
    {
        $this->orderRepository = $orderRepository;
        $this->invoiceFactory = $invoiceFactory;
    }

    public function generateInvoices()
    {
        $orders = $this->orderRepository->getUninvoicedOrders();

        $invoices = [];

        foreach ($orders as $order) {
            $invoices[] = $this-$this->invoiceFactory->createFromOrder($order);
        }

        return $invoices;
    }
}
