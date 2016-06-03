<?php

namespace CsvBundle\Event;

use CsvBundle\Entity\Product;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ProductEvent
 * @package CsvBundle\Event
 */
class ProductEvent extends Event implements ProductEventInterface
{
    
    protected $product; //product entity

    /**
     * Set Product
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get Product
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
}