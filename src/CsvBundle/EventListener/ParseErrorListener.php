<?php

namespace CsvBundle\EventListener;

use CsvBundle\Event\ParseErrorEvent;
use Psr\Log\LoggerInterface;

/**
 * Class ParseErrorListener
 * @package CsvBundle\EventListener
 */
class ParseErrorListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ParseErrorListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Method run when dispatching a parse error
     * @param ParseErrorEvent $event
     */
    public function onParseError(ParseErrorEvent $event)
    {
        $lines = $event->getLines();

        $message = sprintf('PARSE ERROR. Lines: %s', implode(', ', $lines));

        $this->logger->error($message); //logging
    }
}