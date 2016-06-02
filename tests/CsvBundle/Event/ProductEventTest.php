<?php

namespace Tests\CsvBundle\Event;

use CsvBundle\Entity\Product;
use CsvBundle\Event\ProductEvent;

class ProductEventTest extends \PHPUnit_Framework_TestCase
{
    protected $event;

    public function setUp()
    {
        $this->event = new ProductEvent();
    }

    public function testEmptyEvent()
    {
        $this->assertNull($this->event->getProduct());
    }

    public function testEventWithProduct()
    {
        $product = new Product();

        $this->event->setProduct($product);

        $this->assertEquals($product, $this->event->getProduct());
    }

}