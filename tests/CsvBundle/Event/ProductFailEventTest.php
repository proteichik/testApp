<?php

namespace Tests\CsvBundle\Event;

use CsvBundle\Entity\Product;
use CsvBundle\Event\ProductFailEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductFailEventTest extends \PHPUnit_Framework_TestCase
{
    private $event;
    
    public function setUp()
    {
        $this->event = new ProductFailEvent();
    }
    
    public function testEmptyEvent()
    {
        $this->assertNull($this->event->getErrors());
    }

    public function testEventWithErrors()
    {
        $errors = $this->getMock('Symfony\Component\Validator\ConstraintViolationList');

        $this->event->setErrors($errors);

        $this->assertTrue($this->event->getErrors() instanceof ConstraintViolationListInterface);
    }

    public function testEventWithProduct()
    {
        $product = new Product();

        $this->event->setProduct($product);

        $this->assertEquals($product, $this->event->getProduct());
    }
}