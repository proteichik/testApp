<?php

namespace Tests\CsvBundle\Event;

use CsvBundle\Event\ParseErrorEvent;

class ParseErrorEventTest extends \PHPUnit_Framework_TestCase
{
    private $event;

    public function setUp()
    {
        $this->event = new ParseErrorEvent();
    }

    public function testEmptyEvent()
    {
        $this->assertEquals(array(), $this->event->getLines());
    }

    public function testEventWithLines()
    {
        try {
            $this->event->setLines('111');
            $this->fail('Must throw Exception');
        } catch(\TypeError $ex) {
            $this->event->setLines(array('1', '2'));
            $this->assertEquals(array('1', '2'), $this->event->getLines());
        }
    }
}