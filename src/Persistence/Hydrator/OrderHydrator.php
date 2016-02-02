<?php

namespace CleanPhp\Invoicer\Persistence\Hydrator;

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
        return $this->hydrator->extract($object);
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
        $this->hydrator->hydrate($data, $object);

        if (isset($data['customer_id'])) {
            $object->setCustomer(
                $this->customerRepository->getById($data['customer_id'])
            );
        }

        return $object;
    }
}
