<?php

namespace CsvBundle\Service;

use Ddeboer\DataImport\Reader\ReaderInterface;
use CsvBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use CsvBundle\Event\ProductFailEvent;
use CsvBundle\Event\ParseErrorEvent;

/**
 * Class ImportService
 * @package CsvBundle\Service
 */
class ImportService
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var TraceableEventDispatcher
     */
    protected $dispatcher;

    /**
     * ImportService constructor.
     * @param ValidatorInterface $validator
     * @param EntityManager $em
     * @param TraceableEventDispatcher $dispatcher
     */
    public function __construct(ValidatorInterface $validator, EntityManager $em, TraceableEventDispatcher $dispatcher)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Main method for importing objects
     *
     * @param ReaderInterface $reader
     * @param bool $isTest
     * @return array
     */
    public function importObjects(ReaderInterface $reader, $isTest = false)
    {
        $result = array('success' => 0, 'errors' => 0);

        if ( method_exists($reader, 'hasErrors') && $reader->hasErrors()) {
            $result['errors'] += count($this->getErrors($reader)); //increment count of error object
            $this->onParseErrors($this->getErrors($reader)); //Run parse error event
        }

        foreach ($reader as $row)
        {
            $product = new Product();
            $product->setData($row);

            $errors = $this->validator->validate($product);

            if (0 === count($errors))
            {
                $this->em->persist($product);
                $result['success']++; //increment count of success object
            } else {
                $result['errors']++; //increment count of error object
                $this->onFailImport($product, $errors); //Run import error event
            }
        }

        if (!$isTest) {
            try {
                $this->em->flush(); //insert object in DB
            } catch(\Exception $ex)
            {
                //Import fail! :)
                $result['success'] = 0;
                $result['errors'] = $reader->count();
            }
        }

        return $result;
    }

    /**
     * Run import error event
     *
     * @param Product $product
     * @param $errors
     */
    protected function onFailImport(Product $product, $errors)
    {
        $event = new ProductFailEvent();
        $event->setProduct($product);
        $event->setErrors($errors);

        $this->dispatcher->dispatch(ProductFailEvent::FAIL_IMPORT, $event);
    }

    /**
     * Run parse error event
     *
     * @param array $errors
     */
    protected function onParseErrors(array $errors)
    {
        $event = new ParseErrorEvent();
        $event->setLines(array_keys($errors));
        
        $this->dispatcher->dispatch(ParseErrorEvent::PARSE_ERROR, $event);
    }

    /**
     * Get parse error
     *
     * @param ReaderInterface $reader
     * @return array
     */
    protected function getErrors(ReaderInterface $reader)
    {
        return (method_exists($reader, 'getErrors')) ? $reader->getErrors() : array();
    }

    /**
     * Clearing the table
     */
    public function clearProductTable()
    {
        $this->em->getRepository('CsvBundle:Product')->clearTable();
    }

    
    
    


}