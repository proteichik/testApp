<?php

namespace CsvBundle\Event;

use CsvBundle\Entity\Product;
use Symfony\Component\EventDispatcher\Event;

class ProductEvent extends Event implements ProductEventInterface
{
    
    protected $product;

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
}