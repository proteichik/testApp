<?php

namespace CsvBundle\EventListener;

use CsvBundle\Event\ParseErrorEvent;
use Psr\Log\LoggerInterface;

class ParseErrorListener
{
    
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onParseError(ParseErrorEvent $event)
    {
        $lines = $event->getLines();

        $message = sprintf('PARSE ERROR. Lines: %s', implode(', ', $lines));

        $this->logger->error($message);
    }
}