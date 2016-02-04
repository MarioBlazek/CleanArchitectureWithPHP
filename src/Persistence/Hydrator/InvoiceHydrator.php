<?php

namespace CleanPhp\Invoicer\Persistence\Hydrator;

use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use CleanPhp\Invoicer\Persistence\Hydrator\Strategy\DateStrategy;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class InvoiceHydrator
 * @package CleanPhp\Invoicer\Persistence\Hydrator
 */
class InvoiceHydrator implements HydratorInterface
{
    /**
     * @var HydratorInterface
     */
    private $wrappedHydrator;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * InvoiceHydrator constructor.
     * @param HydratorInterface         $wrappedHydrator
     * @param OrderRepositoryInterface  $orderRepository
     */
    public function __construct(HydratorInterface $wrappedHydrator, OrderRepositoryInterface $orderRepository )
    {
        $this->wrappedHydrator = $wrappedHydrator;
        $this->wrappedHydrator->addStrategy('invoice_date', new DateStrategy());
        $this->orderRepository = $orderRepository;
    }

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $data = $this->wrappedHydrator->extract($object);

        if (array_key_exists('order', $data) && !empty($data['order'])) {
            $data['order_id'] = $data['order']->getId();
            unset($data['order']);
        }

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $order = null;

        if (isset($data['order'])) {
            $order = $this->wrappedHydrator->hydrate($data['order'], new Order());

            unset($data['order']);
        }

        if (isset($data['order_id'])) {
            $order = $this->orderRepository->getById($data['order_id']);
        }

        $object = $this->wrappedHydrator->hydrate($data, $object);

        if ($object) {
            $object->setOrder($order);
        }

        return $object;
    }
}
