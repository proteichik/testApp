<?php

namespace CsvBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ProductRepository
 * @package CsvBundle\Repository
 */
class ProductRepository extends EntityRepository
{
    /**
     * Clearing a product table
     * @return array
     */
    public function clearTable()
    {
        return $this->getEntityManager()
        ->createQuery(
            'DELETE CsvBundle:Product p'
        )
        ->getResult();
    }
}