<?php

namespace Tests\CsvBundle\EventListener;

use CsvBundle\Entity\Product;
use CsvBundle\EventListener\ProductFailListener;
use CsvBundle\Event\ProductFailEvent;

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

            $errors = $this->getMockBuilder('Symfony\Component\Validator\ConstraintViolationList')->disableOriginalConstructor()->getMock();

            $event->setProduct($product);
            $event->setErrors($errors);

            $errors->expects($this->at(0))->method('offsetExists')->will($this->returnValue(true));
            $errors->expects($this->at(0))->method('offsetGet')->will($this->returnValue('test'));
            $this->logger->expects($this->once())->method('warning')->with($this->identicalTo('The product test (code: P1111) was not imported. Errors: ["test"]'));

            $this->listener->onFailImport($event);

        }
    }


}