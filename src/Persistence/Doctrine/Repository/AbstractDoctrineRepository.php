<?php

namespace CleanPhp\Invoicer\Persistence\Doctrine\Repository;

use CleanPhp\Invoicer\Domain\Entity\AbstractEntity;
use CleanPhp\Invoicer\Domain\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class AbstractDoctrineRepository
 * @package CleanPhp\Invoicer\Persistence\Doctrine\Repository
 */
class AbstractDoctrineRepository implements RepositoryInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * AbstractDoctrineRepository constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        if (empty($this->entityClass)) {
            throw new \RuntimeException(
                get_class($this) . '::$entityClass is not defined'
            );
        }
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @return null|object
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function getById($id)
    {
        return $this->em->find($this->entityClass, $id);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->em->getRepository($this->entityClass)
            ->findAll();
    }

    /**
     * @param array $conditions
     * @param array $order
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getBy(
        $conditions = [],
        $order = [],
        $limit = null,
        $offset = null
    )
    {
        $repository = $this->em->getRepository($this->entityClass);

        $results = $repository->findBy($conditions, $order, $limit, $offset);

        return $results;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return $this
     */
    public function persist(AbstractEntity $entity)
    {
        $this->em->persist($entity);

        return $this;
    }

    /**
     * @return $this
     */
    public function begin()
    {
        $this->em->beginTransaction();

        return $this;
    }

    /**
     * @return $this
     */
    public function commit()
    {
        $this->em->flush();
        $this->em->commit();

        return $this;
    }
}
