<?php

namespace CsvBundle\EventListener;

use CsvBundle\Event\ProductFailEvent;
use Psr\Log\LoggerInterface;

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

        /*$err_str = '';
        foreach ($errors as $error) {
            $err_str .= $error->getMessage() . '. ';
        }*/

        $message = sprintf('The product %s (code: %s) was not imported. Errors: %s', $product->getStrProductName(), $product->getStrProductCode(), $errors);

        $this->logger->warning($message);
    }
}