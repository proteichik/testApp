<?php

namespace Tests\CsvBundle\Event;

use CsvBundle\Entity\Product;
use CsvBundle\Event\ProductFailEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ProductFailEventTest
 * @package Tests\CsvBundle\Event
 */
class ProductFailEventTest extends \PHPUnit_Framework_TestCase
{
    private $event;
    
    public function setUp()
    {
        $this->event = new ProductFailEvent();
    }

    /**
     * Testing empty event
     */
    public function testEmptyEvent()
    {
        $this->assertNull($this->event->getErrors());
    }

    /**
     * Testing event with validation errors
     */
    public function testEventWithErrors()
    {
        $errors = $this->getMock('Symfony\Component\Validator\ConstraintViolationList');

        $this->event->setErrors($errors);

        $this->assertTrue($this->event->getErrors() instanceof ConstraintViolationListInterface);
    }

    /**
     * Testing event with product entity
     */
    public function testEventWithProduct()
    {
        $product = new Product();

        $this->event->setProduct($product);

        $this->assertEquals($product, $this->event->getProduct());
    }
}