<?php

namespace Tests\CsvBundle\EventListener;

use CsvBundle\Entity\Product;
use CsvBundle\EventListener\ProductFailListener;
use CsvBundle\Event\ProductFailEvent;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ProductfailListenerTest extends \PHPUnit_Framework_TestCase
{
    private $listener;
    private $logger;

    public function setUp()
    {
        $this->logger = $this->getMockBuilder('Symfony\Bridge\Monolog\Logger')->disableOriginalConstructor()->getMock();

        $this->listener = new ProductFailListener($this->logger);
    }

    public function testOnParseError()
    {
        $bad_event = new \stdClass();

        try {
            $this->listener->onFailImport($bad_event);
            $this->fail('Must throw TypeError');
        } catch (\TypeError $ex) {
            $event = new ProductFailEvent();

            $product = new Product();
            $product->setStrProductName('test');
            $product->setStrProductCode('P1111');

            $errors = new ConstraintViolationList(array($this->getViolation('test', $product)));

            $event->setProduct($product);
            $event->setErrors($errors);

            $this->logger->expects($this->once())->method('warning')->with($this->identicalTo('The product test (code: P1111) was not imported. Errors:'), $this->identicalTo(array('test')));

            $this->listener->onFailImport($event);

        }
    }

    protected function getViolation($message, $root = null, $propertyPath = null)
    {
        return new ConstraintViolation($message, $message, array(), $root, $propertyPath, null);
    }


}