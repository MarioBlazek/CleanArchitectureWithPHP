<?php

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class InvoicesController
 * @package Application\Controller
 */
class InvoicesController extends AbstractActionController
{
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoices;
    /**
     * @var OrderRepositoryInterface
     */
    private $orders;
    /**
     * @var InvoicingService
     */
    private $invoicing;

    /**
     * InvoicesController constructor.
     * @param InvoiceRepositoryInterface    $invoices
     * @param OrderRepositoryInterface      $orders
     * @param InvoicingService              $invoicing
     */
    public function __construct(
        InvoiceRepositoryInterface $invoices,
        OrderRepositoryInterface $orders,
        InvoicingService $invoicing
    )
    {
        $this->invoices = $invoices;
        $this->orders = $orders;
        $this->invoicing = $invoicing;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $invoices = $this->invoices->getAll();

        return [
            'invoices' => $invoices,
        ];
    }

    /**
     * @return array
     */
    public function generateAction()
    {
        return [
            'orders' => $this->orders->getUninvoicedOrders(),
        ];
    }

    /**
     * @return array
     */
    public function generateProcessAction()
    {
        $invoices = $this->invoicing->generateInvoices();

        $this->invoices->begin();

        foreach($invoices as $invoice) {
            $this->invoices->persist($invoice);
        }

        $this->invoices->commit();

        return [
            'invoices' => $invoices,
        ];
    }

    /**
     * @return array|null
     */
    public function viewAction()
    {
        $id = $this->params()->fromRoute('id');
        $invoice = $this->invoices->getById($id);

        if (!$invoice) {
            $this->getResponse()->setStatusCode(404);

            return null;
        }

        return [
            'invoice'   => $invoice,
            'order'     => $invoice->getOrder(),
        ];
    }
}
