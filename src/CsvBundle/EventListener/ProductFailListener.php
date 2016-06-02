<?php

namespace CsvBundle\EventListener;

use CsvBundle\Event\ProductFailEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductFailListener
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onFailImport(ProductFailEvent $event)
    {
        $product = $event->getProduct();
        $errors = $event->getErrors();

        $err = array();
        foreach ($errors as $error) {
            $err[] = $error->getMessage();
        }

        $message = sprintf('The product %s (code: %s) was not imported. Errors:', $product->getStrProductName(), $product->getStrProductCode());

        $this->logger->warning($message, $err);
    }

    
}