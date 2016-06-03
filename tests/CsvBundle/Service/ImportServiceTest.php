<?php

namespace Tests\CsvBundle\Service;

use CsvBundle\Service\ImportService;
use Ddeboer\DataImport\Reader\CsvReader;
use CsvBundle\Event\ParseErrorEvent;
use CsvBundle\Event\ProductFailEvent;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class ImportServiceTest
 * @package Tests\CsvBundle\Service
 */
class ImportServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImportService
     */
    private $service;

    /**
     * @var Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher
     */
    private $dispatcher;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var Symfony\Component\Validator\Validator\RecursiveValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = $this->getMockBuilder('Symfony\Component\Validator\Validator\RecursiveValidator')->disableOriginalConstructor()->getMock();
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher')->disableOriginalConstructor()->getMock();

        $this->service = new ImportService($this->validator, $this->em, $this->dispatcher);
    }

    /**
     * Testing import csv file with parse error
     */
    public function testCsvParseError()
    {
        $csvReader = $this->getReader(__DIR__.'/../Fixtures/stock_with_parse_error.csv');

        // Expected that dispatcher's method dispatch run once with arguments:
        $this->dispatcher->expects($this->once())->method('dispatch')->with($this->identicalTo(ParseErrorEvent::PARSE_ERROR),
            $this->isInstanceOf(ParseErrorEvent::class));

        //Expected that validator's method validate run and return no errors
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue(array()));

        //Expected that entity manager's method flush never run (because isTest===true)
        $this->em->expects($this->never())->method('flush');

        $result = $this->service->importObjects($csvReader, true);

        $this->assertArraySubset(array('errors' => 2), $result);
    }

    /**
     * Testing import csv file without parse error, but with import error
     */
    public function testCsvWithoutParseErrorButWithImportError()
    {
        $csvReader = $this->getReader(__DIR__.'/../Fixtures/stock_valid.csv');

        $constList = new ConstraintViolationList(array($this->getViolation('test')));

        // Expected that dispatcher's method dispatch run with arguments:
        $this->dispatcher->expects($this->any())->method('dispatch')->with($this->identicalTo(ProductFailEvent::FAIL_IMPORT),
            $this->isInstanceOf(ProductFailEvent::class));

        //Expected that validator's method validate run and return test errors
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue($constList));

        //Expected that entity manager's method flush never run (because isTest===true)
        $this->em->expects($this->never())->method('flush');

        //Expected that entity manager's method persist never run
        $this->em->expects($this->never())->method('persist');

        $result = $this->service->importObjects($csvReader, true);

        $this->assertEquals($result['errors'], $csvReader->count());
    }

    /**
     * Testing a clear table method
     */
    public function testClearTable()
    {
        $repository = $this->getMockBuilder('CsvBundle\Repository\ProductRepository')->disableOriginalConstructor()->getMock();

        $this->em->expects($this->once())->method('getRepository')->with($this->identicalTo('CsvBundle:Product'))->will($this->returnValue($repository));

        $this->service->clearProductTable();
    }

    /**
     * Get reader for csv file
     *
     * @param $path
     * @return CsvReader
     */
    private function getReader($path)
    {
        $file = new \SplFileObject($path);
        $csvReader = new CsvReader($file);
        $csvReader->setHeaderRowNumber(0);

        return $csvReader;
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