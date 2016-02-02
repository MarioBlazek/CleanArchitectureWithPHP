<?php

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use CleanPhp\Invoicer\Service\InputFilter\OrderInputFilter;
use Zend\Mvc\Controller\AbstractActionController;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Zend\View\Model\ViewModel;

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
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var OrderInputFilter
     */
    private $orderInputFilter;
    /**
     * @var OrderHydrator
     */
    private $hydrator;

    /**
     * OrdersController constructor.
     *
     * @param OrderRepositoryInterface      $orders
     * @param CustomerRepositoryInterface   $customerRepository
     * @param OrderInputFilter              $orderInputFilter
     * @param OrderHydrator                 $hydrator
     */
    public function __construct(
        OrderRepositoryInterface $orders,
        CustomerRepositoryInterface $customerRepository,
        OrderInputFilter $orderInputFilter,
        OrderHydrator $hydrator
    )
    {
        $this->orders = $orders;
        $this->customerRepository = $customerRepository;
        $this->orderInputFilter = $orderInputFilter;
        $this->hydrator = $hydrator;
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

    /**
     * @return array|null
     */
    public function viewAction()
    {
        $id = $this->params()->fromRoute('id');
        $order = $this->orders->getById($id);

        if (!$order) {
            $this->getResponse()->setStatusCode(404);

            return null;
        }

        return [
            'order' => $order,
        ];
    }

    /**
     * @return ViewModel
     */
    public function newAction()
    {
        $viewModel = new ViewModel();
        $order = new Order();

        if ($this->getRequest()->isPost()) {

            $this->orderInputFilter->setData($this->params()->fromPost());

            if ($this->orderInputFilter->isValid()) {

                $order = $this->hydrator->hydrate(
                    $this->orderInputFilter->getValues(),
                    $order
                );

                $this->orders->begin()
                    ->persist($order)
                    ->commit();

                $this->flashMessenger()->addSuccessMessage('Order Created');

                $this->redirect()->toUrl('/orders/view/' . $order->getId());
            } else {

                $this->hydrator->hydrate(
                    $this->params()->fromPost(),
                    $order
                );

                $viewModel->setVariable(
                    'errors',
                    $this->orderInputFilter->getMessages()
                );
            }
        }

        $viewModel->setVariable(
            'customers',
            $this->customerRepository->getAll()
        );

        $viewModel->setVariable('order', $order);

        return $viewModel;
    }
}
