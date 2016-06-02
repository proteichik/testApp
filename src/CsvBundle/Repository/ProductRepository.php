<?php

namespace CsvBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function clearTable()
    {
        return $this->getEntityManager()
        ->createQuery(
            'DELETE CsvBundle:Product p'
        )
        ->getResult();
    }
}