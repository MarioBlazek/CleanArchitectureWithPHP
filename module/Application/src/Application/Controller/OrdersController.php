<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;

/**
 * Class OrdersController
 * @package Application\Controller
 */
class OrdersController extends AbstractActionController
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orders;

    /**
     * OrdersController constructor.
     * @param OrderRepositoryInterface $orders
     */
    public function __construct(OrderRepositoryInterface $orders)
    {
        $this->orders = $orders;
    }

    /**
     * {@inheritdoc}
     */
    public function indexAction()
    {
        return [
            'orders' => $this->orders->getAll(),
        ];
    }
}
