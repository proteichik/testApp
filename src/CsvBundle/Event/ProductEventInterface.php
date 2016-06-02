<?php

namespace CsvBundle\Event;

use CsvBundle\Entity\Product;

interface ProductEventInterface
{
    public function setProduct(Product $product);
    public function getProduct();
}