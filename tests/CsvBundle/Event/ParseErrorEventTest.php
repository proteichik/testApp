<?php

namespace Tests\CsvBundle\Event;

use CsvBundle\Event\ParseErrorEvent;

/**
 * Class ParseErrorEventTest
 * @package Tests\CsvBundle\Event
 */
class ParseErrorEventTest extends \PHPUnit_Framework_TestCase
{
    private $event;

    public function setUp()
    {
        $this->event = new ParseErrorEvent();
    }

    /**
     * Testing empty event
     */
    public function testEmptyEvent()
    {
        $this->assertEquals(array(), $this->event->getLines());
    }

    /**
     * Testing event with lines
     */
    public function testEventWithLines()
    {
        try {
            //simulate type error
            $this->event->setLines('111');
            $this->fail('Must throw TypeError');
        } catch(\TypeError $ex) {
            $this->event->setLines(array('1', '2'));
            $this->assertEquals(array('1', '2'), $this->event->getLines());
        }
    }
}