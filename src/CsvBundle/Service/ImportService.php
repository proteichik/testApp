<?php

namespace CsvBundle\Service;

use Ddeboer\DataImport\Reader\ReaderInterface;
use CsvBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use CsvBundle\Event\ProductFailEvent;
use CsvBundle\Event\ParseErrorEvent;


class ImportService
{
    protected $validator;
    protected $em;
    protected $dispatcher;

    public function __construct(ValidatorInterface $validator, EntityManager $em, TraceableEventDispatcher $dispatcher)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function importObjects(ReaderInterface $reader, $isTest = false)
    {
        $result = array('success' => 0, 'errors' => 0);

        if ( method_exists($reader, 'hasErrors') && $reader->hasErrors()) {
            $result['errors'] += count($this->getErrors($reader));
            $this->onParseErrors($this->getErrors($reader));
        }

        foreach ($reader as $row)
        {
            $product = new Product();
            $product->setData($row);

            $errors = $this->validator->validate($product);

            if (0 === count($errors))
            {
                $this->em->persist($product);
                $result['success']++;
            } else {
                $result['errors']++;
                $this->onFailImport($product, $errors);
            }
        }

        if (!$isTest) {
            $this->em->flush();
        }

        return $result;
    }

    protected function onFailImport(Product $product, $errors)
    {
        $event = new ProductFailEvent();
        $event->setProduct($product);
        $event->setErrors($errors);

        $this->dispatcher->dispatch(ProductFailEvent::FAIL_IMPORT, $event);
    }
    
    protected function onParseErrors(array $errors)
    {
        $event = new ParseErrorEvent();
        $event->setLines(array_keys($errors));
        
        $this->dispatcher->dispatch(ParseErrorEvent::PARSE_ERROR, $event);
    }

    protected function getErrors(ReaderInterface $reader)
    {
        return (method_exists($reader, 'getErrors')) ? $reader->getErrors() : array();
    }

    public function clearProductTable()
    {
        $this->em->getRepository('CsvBundle:Product')->clearTable();
    }

    
    
    


}