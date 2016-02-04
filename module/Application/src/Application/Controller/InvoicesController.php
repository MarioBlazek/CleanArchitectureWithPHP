<?php

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
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
     * InvoicesController constructor.
     * @param InvoiceRepositoryInterface $invoices
     */
    public function __construct(InvoiceRepositoryInterface $invoices)
    {
        $this->invoices = $invoices;
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
}
