<?php

namespace CleanPhp\Invoicer\Persistence\Hydrator;

use CleanPhp\Invoicer\Domain\Entity\Customer;
use Zend\Stdlib\Hydrator\HydratorInterface;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;

/**
 * Class OrderHydrator
 * @package CleanPhp\Invoicer\Persistence\Hydrator
 */
class OrderHydrator implements HydratorInterface
{
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    public function __construct(HydratorInterface $hydrator, CustomerRepositoryInterface $customerRepository)
    {
        $this->hydrator = $hydrator;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $data = $this->hydrator->extract($object);

        if (array_key_exists('customer', $data) && !empty($data['customer'])) {

            $data['customer_id'] = $data['customer']->getId();

            unset($data['customer']);
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
        $customer = null;

        $this->hydrator->hydrate($data, $object);

        if (isset($data['customer']) && isset($data['customer']['id'])) {

            $data['customer'] = $this->customerRepository->getById($data['customer']['id']);
        }

        return $this->hydrator->hydrate($data, $object);
    }
}
