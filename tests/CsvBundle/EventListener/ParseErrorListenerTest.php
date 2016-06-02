<?php

namespace Tests\CsvBundle\EventListener;

use CsvBundle\EventListener\ParseErrorListener;
use CsvBundle\Event\ParseErrorEvent;

class ParseErrorListenerTest extends \PHPUnit_Framework_TestCase
{
    private $listener;
    private $logger;

    public function setUp()
    {
        $this->logger = $this->getMockBuilder('Symfony\Bridge\Monolog\Logger')->disableOriginalConstructor()->getMock();

        $this->listener = new ParseErrorListener($this->logger);
    }

    public function testOnParseError()
    {
        $bad_event = new \stdClass();

        try {
            $this->listener->onParseError($bad_event);
            $this->fail('Must throw TypeError');
        } catch (\TypeError $ex) {
            $event = new ParseErrorEvent();
            $event->setLines(array('1', '2'));
            $this->logger->expects($this->once())->method('error')->with($this->identicalTo('PARSE ERROR. Lines: 1, 2'));

            $this->listener->onParseError($event);

        }
    }


}