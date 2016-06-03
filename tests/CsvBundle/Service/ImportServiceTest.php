<?php

namespace Tests\CsvBundle\Service;

use CsvBundle\Service\ImportService;
use Ddeboer\DataImport\Reader\CsvReader;
use CsvBundle\Event\ParseErrorEvent;
use CsvBundle\Event\ProductFailEvent;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

class ImportServiceTest extends \PHPUnit_Framework_TestCase
{
    private $service;
    private $dispatcher;
    private $em;
    private $validator;

    public function setUp()
    {
        $this->validator = $this->getMockBuilder('Symfony\Component\Validator\Validator\RecursiveValidator')->disableOriginalConstructor()->getMock();
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher')->disableOriginalConstructor()->getMock();

        $this->service = new ImportService($this->validator, $this->em, $this->dispatcher);
    }

    public function testCsvParseError()
    {
        $csvReader = $this->getReader(__DIR__.'/../Fixtures/stock_with_parse_error.csv');

        $this->dispatcher->expects($this->once())->method('dispatch')->with($this->identicalTo(ParseErrorEvent::PARSE_ERROR),
            $this->isInstanceOf(ParseErrorEvent::class));
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue(array()));
        $this->em->expects($this->never())->method('flush');

        $result = $this->service->importObjects($csvReader, true);

        $this->assertArraySubset(array('errors' => 2), $result);
    }

    public function testCsvWithoutParseErrorButWithImportError()
    {
        $csvReader = $this->getReader(__DIR__.'/../Fixtures/stock_valid.csv');

        $constList = new ConstraintViolationList(array($this->getViolation('test')));

        $this->dispatcher->expects($this->any())->method('dispatch')->with($this->identicalTo(ProductFailEvent::FAIL_IMPORT),
            $this->isInstanceOf(ProductFailEvent::class));
        $this->validator->expects($this->any())->method('validate')->will($this->returnValue($constList));
        $this->em->expects($this->never())->method('flush');

        $result = $this->service->importObjects($csvReader, true);

        $this->assertEquals($result['errors'], $csvReader->count());
    }

    public function testClearTable()
    {
        $repository = $this->getMockBuilder('CsvBundle\Repository\ProductRepository')->disableOriginalConstructor()->getMock();

        $this->em->expects($this->once())->method('getRepository')->with($this->identicalTo('CsvBundle:Product'))->will($this->returnValue($repository));

        $this->service->clearProductTable();
    }

    private function getReader($path)
    {
        $file = new \SplFileObject($path);
        $csvReader = new CsvReader($file);
        $csvReader->setHeaderRowNumber(0);

        return $csvReader;
    }

    protected function getViolation($message, $root = null, $propertyPath = null)
    {
        return new ConstraintViolation($message, $message, array(), $root, $propertyPath, null);
    }


}