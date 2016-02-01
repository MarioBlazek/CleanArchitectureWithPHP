<?php

namespace CleanPhp\Invoicer\Domain\Repository;

use CleanPhp\Invoicer\Domain\Entity\AbstractEntity;

interface RepositoryInterface
{
    public function getById($id);

    public function getAll();

    public function persist(AbstractEntity $entity);

    public function begin();

    public function commit();
}
