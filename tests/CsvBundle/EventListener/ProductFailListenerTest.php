<?php

namespace Tests\CsvBundle\EventListener;

use CsvBundle\Entity\Product;
use CsvBundle\EventListener\ProductFailListener;
use CsvBundle\Event\ProductFailEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ProductFailListenerTest
 * @package Tests\CsvBundle\EventListener
 */
class ProductFailListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductFailListener
     */
    private $listener;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function setUp()
    {
        $this->logger = $this->getMockBuilder('Symfony\Bridge\Monolog\Logger')->disableOriginalConstructor()->getMock();

        $this->listener = new ProductFailListener($this->logger);
    }

    /**
     * Testing onFailImport
     */
    public function testOnFailImport()
    {
        $bad_event = new \stdClass();

        try {
            //simulate type error
            $this->listener->onFailImport($bad_event);
            $this->fail('Must throw TypeError');
        } catch (\TypeError $ex) {
            $event = new ProductFailEvent();

            $product = new Product();
            $product->setStrProductName('test');
            $product->setStrProductCode('P1111');

            $errors = new ConstraintViolationList(array($this->getViolation('test', $product))); //get test validation error

            $event->setProduct($product);
            $event->setErrors($errors);

            //Expected that logger's method warning run once with this arguments:
            $this->logger->expects($this->once())->method('warning')->with($this->identicalTo('The product test (code: P1111) was not imported. Errors:'), $this->identicalTo(array('test')));

            $this->listener->onFailImport($event);

        }
    }

    /**
     * Get Constraint Violation
     *
     * @param $message
     * @param null $root
     * @param null $propertyPath
     * @return ConstraintViolation
     */
    protected function getViolation($message, $root = null, $propertyPath = null)
    {
        return new ConstraintViolation($message, $message, array(), $root, $propertyPath, null);
    }


}