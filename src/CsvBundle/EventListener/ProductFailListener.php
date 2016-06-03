<?php

namespace CsvBundle\EventListener;

use CsvBundle\Event\ProductFailEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ProductFailListener
 * @package CsvBundle\EventListener
 */
class ProductFailListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ProductFailListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Method run when dispatching a import error
     * @param ProductFailEvent $event
     */
    public function onFailImport(ProductFailEvent $event)
    {
        $product = $event->getProduct();
        $errors = $event->getErrors();

        $err = array();
        foreach ($errors as $error) {
            $err[] = $error->getMessage(); //get array of errors message
        }

        $message = sprintf('The product %s (code: %s) was not imported. Errors:', $product->getStrProductName(), $product->getStrProductCode());

        $this->logger->warning($message, $err);
    }

    
}