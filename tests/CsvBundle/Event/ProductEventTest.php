<?php

namespace Tests\CsvBundle\Event;

use CsvBundle\Entity\Product;
use CsvBundle\Event\ProductEvent;

/**
 * Class ProductEventTest
 * @package Tests\CsvBundle\Event
 */
class ProductEventTest extends \PHPUnit_Framework_TestCase
{
    protected $event;

    public function setUp()
    {
        $this->event = new ProductEvent();
    }

    /**
     * Testing empty event
     */
    public function testEmptyEvent()
    {
        $this->assertNull($this->event->getProduct());
    }

    /**
     * Testing event with data
     */
    public function testEventWithProduct()
    {
        $product = new Product();

        $this->event->setProduct($product);

        $this->assertEquals($product, $this->event->getProduct());
    }

}